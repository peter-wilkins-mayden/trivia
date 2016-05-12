<?php
function echoln($string)
{
    echo $string . "\n";
}

class Game
{

    var $players;
    var $places;
    var $purses;
    var $inPenaltyBox;

    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;

    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    const SCIENCE = "Science";

    const SPORTS = "Sports";

    const ROCK = "Rock";

    const POP = "Pop";

    const BoardSize = 11;

    function __construct()
    {

        $this->players = array();
        $this->places = array(0);
        $this->purses = array(0);
        $this->inPenaltyBox = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
			array_push($this->popQuestions, "Pop Question " . $i);
			array_push($this->scienceQuestions, ("Science Question " . $i));
			array_push($this->sportsQuestions, ("Sports Question " . $i));
			array_push($this->rockQuestions, ("Rock Question " . $i));
    	}
    }


    function isPlayable()
    {
        return ($this->howManyPlayers() >= 2);
    }

    function add($playerName)
    {
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . count($this->players));
        return true;
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function roll($roll)
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
                $this->DefautRollStuff($roll);
            } else {
                echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }

        } else {

            $this->DefautRollStuff($roll);
        }

    }

    function askQuestion()
    {
        if ($this->currentCategory() == self::POP) {
            echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == self::SCIENCE) {
            echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == self::SPORTS) {
            echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == self::ROCK) {
            echoln(array_shift($this->rockQuestions));
        }
    }


    function currentCategory()
    {
        $lookup = [self::POP, self::SCIENCE, self::SPORTS, self::ROCK];
        if ($this->places[$this->currentPlayer] % 4 == 0) {
            return $lookup[0];
        }

        if ($this->places[$this->currentPlayer] % 4 == 1) {
            return $lookup[1];
        }

        if ($this->places[$this->currentPlayer] % 4 == 2) {
            return $lookup[2];
        }

        return $lookup[3];
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                $this->purses[$this->currentPlayer]++;
                echoln($this->players[$this->currentPlayer]
                    . " now has "
                    . $this->purses[$this->currentPlayer]
                    . " Gold Coins.");

                $winner = $this->didPlayerWin();
                $this->currentPlayer++;
                if ($this->currentPlayer == count($this->players)) {
                    $this->currentPlayer = 0;
                }

                return $winner;
            } else {
                $this->currentPlayer++;
                if ($this->currentPlayer == count($this->players)) {
                    $this->currentPlayer = 0;
                }
                return true;
            }


        } else {

            echoln("Answer was corrent!!!!");
            $this->purses[$this->currentPlayer]++;
            echoln($this->players[$this->currentPlayer]
                . " now has "
                . $this->purses[$this->currentPlayer]
                . " Gold Coins.");

            $winner = $this->didPlayerWin();
            $this->currentPlayer++;
            if ($this->currentPlayer == count($this->players)) {
                $this->currentPlayer = 0;
            }

            return $winner;
        }
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
        return true;
    }


    function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == 6);
    }

    /**
     * @param $roll
     */
    private function DefautRollStuff($roll)
    {
        $this->AddRoll($roll);
        if ($this->IsEndOfBoard()) {
            $this->MoveBack12();
        }

        echoln($this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->places[$this->currentPlayer]);
        echoln("The category is " . $this->currentCategory());
        $this->askQuestion();
    }

    /**
     * @param $roll
     */
    private function AddRoll($roll)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
    }

    private function MoveBack12()
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
    }

    /**
     * @return bool
     */
    private function IsEndOfBoard()
    {
        return $this->places[$this->currentPlayer] > (self::BoardSize - 1);
    }
}

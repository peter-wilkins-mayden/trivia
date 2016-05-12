<?php
function echoln($string)
{
    echo $string . "\n";
}

class Game
{

    var $players = [];
    var $places = [0];
    var $purses = [0];
    var $inPenaltyBox = [0];

    var $popQuestions = [];
    var $scienceQuestions = [];
    var $sportsQuestions = [];
    var $rockQuestions = [];

    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    const POP = "Pop";

    const SCIENCE = "Science";

    const SPORTS = "Sports";

    const ROCK = "Rock";

    const BOARD_SIZE = 12;

    const WRONG_ANSWER = 7;

    function __construct()
    {
        $this->generateQuestions();
    }

    function addPlayer($playerName)
    {
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        OutputLayerConsole::outputPlayerAdded($playerName);
        $this->outputPlayerNumber();
        return true;
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function errPlayGame()
    {
        do {
            $dice = rand(0, 5) + 1;
            $this->roll($dice);

            if ($this->givenAnswer() != self::WRONG_ANSWER) {
                if ($this->playerIsInPenaltyBox() &&
                    !$this->isGettingOutOfPenaltyBox
                ) {
                    $notAWinner = true;
                } else {
                    $this->outputCorrectAnswer();
                    $this->purses[$this->currentPlayer]++;
                    $this->outputPlayerBalance();
                    $notAWinner = $this->didPlayerWin();
                }
            } else {
                $this->outputIncorrectAnswer();
                $this->outputPlayerSentToPenaltyBox();
                $this->putPlayerIntoPenaltyBox();
                $notAWinner = true;
            }
            $this->errNextPlayer();

        } while ($notAWinner);
    }

    function roll($roll)
    {
        $this->outputCurrentPlayer();
        $this->outputRoll($roll);

        if ($this->playerIsInPenaltyBox()) {
            if (!$this->rolledAnOddNumber($roll)) {
                $this->isGettingOutOfPenaltyBox = false;
                $this->outputPlayerNotLeavingPenaltyBox();
                return;
            } else {
                $this->isGettingOutOfPenaltyBox = true;
                $this->outputPlayerLavingPenaltyBox();
            }
        }
        $this->moveAlongBoard($roll);
        OutputLayerConsole::outputPlayerLocation(
            $this->players[$this->currentPlayer],
            $this->places[$this->currentPlayer]);
        $this->outputCategory();
        $this->askQuestion();
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
        return [self::POP, self::SCIENCE, self::SPORTS, self::ROCK][$this->places[$this->currentPlayer] % 4];
    }



    function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == 6);
    }

    private function generateQuestions()
    {
        for ($i = 0; $i < 50; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, "Science Question " . $i);
            array_push($this->sportsQuestions, "Sports Question " . $i);
            array_push($this->rockQuestions, "Rock Question " . $i);
        }
    }

    private function goBackToBeginningOfBoard()
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - self::BOARD_SIZE;
    }

    /**
     * @param $roll
     * @return mixed
     */
    private function moveAlongBoard($roll)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
        if ($this->playerHasReachedEndOfBoard()) {
            $this->goBackToBeginningOfBoard();
        }
    }

    /**
     * @return bool
     */
    private function playerHasReachedEndOfBoard()
    {
        return $this->places[$this->currentPlayer] > (self::BOARD_SIZE - 1);
    }

    /**
     * @return mixed
     */
    private function playerIsInPenaltyBox()
    {
        return $this->inPenaltyBox[$this->currentPlayer];
    }

    private function putPlayerIntoPenaltyBox()
    {
        $this->inPenaltyBox[$this->currentPlayer] = true;
    }

    /**
     * @param $roll
     * @return bool
     */
    private function rolledAnOddNumber($roll)
    {
        return $roll % 2 != 0;
    }

    private function outputCategory()
    {
        echoln("The category is " . $this->currentCategory());
    }


    private function outputPlayerNotLeavingPenaltyBox()
    {
        echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
    }

    private function outputPlayerLavingPenaltyBox()
    {
        echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
    }

    private function outputCurrentPlayer()
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
    }

    /**
     * @param $roll
     */
    private function outputRoll($roll)
    {
        echoln("They have rolled a " . $roll);
    }


    private function outputPlayerNumber()
    {
         echoln("They are player number " . count($this->players));
    }

    private function outputCorrectAnswer()
    {
        echoln("Answer was correct!!!!");
    }

    private function outputPlayerBalance()
    {
        echoln($this->players[$this->currentPlayer]
            . " now has "
            . $this->purses[$this->currentPlayer]
            . " Gold Coins.");
    }

    private function outputIncorrectAnswer()
    {
         echoln("Question was incorrectly answered");
    }

    private function outputPlayerSentToPenaltyBox()
    {
         echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
    }

    private function errNextPlayer()
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
    }

    /**
     * @return int
     */
    private function givenAnswer()
    {
        return rand(0, 9);
    }
}

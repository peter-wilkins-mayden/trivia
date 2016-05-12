<?php
function echoln($string)
{
    echo $string . "\n";
}

class Game
{

    private $players = [];
    private $places = [0];
    private $purses = [0];
    private $inPenaltyBox = [0];
    private $currentPlayer = 0;
    private $isGettingOutOfPenaltyBox;
    private $questions;
    const BOARD_SIZE = 12;
    const WRONG_ANSWER = 7;

    public function __construct()
    {
        $this->questions = new Questions();
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function errPlayGame()
    {
        do {
            $roll = rand(0, 5) + 1;
            $this->roll($roll);

            if ($this->givenAnswer() == self::WRONG_ANSWER) {
                OutputLayerConsole::outputIncorrectAnswer();
                OutputLayerConsole::outputPlayerSentToPenaltyBox($this->players[$this->currentPlayer]);
                $this->putPlayerIntoPenaltyBox();
                $notAWinner = true;
            } else {
                if (!$this->playerIsInPenaltyBox() || $this->isGettingOutOfPenaltyBox) {
                    OutputLayerConsole::outputCorrectAnswer();
                    $this->purses[$this->currentPlayer]++;
                    OutputLayerConsole::outputPlayerBalance($this->players[$this->currentPlayer],
                        $this->purses[$this->currentPlayer]);
                    $notAWinner = $this->purses[$this->currentPlayer] != 6;
                }
            }
            $this->errNextPlayer();

        } while ($notAWinner);
    }

    function roll($roll)
    {
        OutputLayerConsole::outputCurrentPlayer($this->players[$this->currentPlayer]);
        OutputLayerConsole::outputRoll($roll);

        if ($this->playerIsInPenaltyBox()) {
            if (!$this->rolledAnOddNumber($roll)) {
                $this->isGettingOutOfPenaltyBox = false;
                OutputLayerConsole::outputPlayerNotLeavingPenaltyBox($this->players[$this->currentPlayer]);
                return;
            } else {
                $this->isGettingOutOfPenaltyBox = true;
                OutputLayerConsole::outputPlayerLavingPenaltyBox($this->players[$this->currentPlayer]);
            }
        }
        $this->moveAlongBoard($roll);
        OutputLayerConsole::outputPlayerLocation(
            $this->players[$this->currentPlayer],
            $this->places[$this->currentPlayer]);
        OutputLayerConsole::outputCategory(
            $this->questions->currentCategory($this->places[$this->currentPlayer]));
        $this->questions->askQuestion($this->places[$this->currentPlayer]);
    }

    private function moveAlongBoard($roll)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
        if ($this->playerHasReachedEndOfBoard($this->places[$this->currentPlayer])) {
            $this->places[$this->currentPlayer] = $this->goBackToBeginningOfBoard($this->places[$this->currentPlayer]);
        }
    }

    private function errNextPlayer()
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
    }

    function addPlayer($playerName)
    {
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        OutputLayerConsole::outputPlayerAdded($playerName);
        OutputLayerConsole::outputPlayerNumber($this->players);
        return true;
    }
    private function playerIsInPenaltyBox()
    {
        return $this->inPenaltyBox[$this->currentPlayer];
    }
    private function putPlayerIntoPenaltyBox()
    {
        $this->inPenaltyBox[$this->currentPlayer] = true;
    }


    private function goBackToBeginningOfBoard($currentPos)
    {
        return $currentPos - self::BOARD_SIZE;
    }
    private function givenAnswer()
    {
        return rand(0, 9);
    }
    private function rolledAnOddNumber($roll)
    {
        return $roll % 2 != 0;
    }
    private function playerHasReachedEndOfBoard($currentPos)
    {
        return $currentPos > (self::BOARD_SIZE - 1);
    }
}

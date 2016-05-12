<?php


class OutputLayerConsole
{

    public static function outputPlayerLocation($player, $place)
    {
        echoln($player
            . "'s new location is "
            . $place);
    }

    /**
     * @param $playerName
     */
    public static function outputPlayerAdded($playerName)
    {
        echoln($playerName . " was added");
    }

    /**
     * @param Game $instance
     */
    public static function outputCategory($cc)
    {
        echoln("The category is " . $cc);
    }

    /**
     * @param Game $instance
     * @param $players
     * @param $currentPlayer
     */
    public static function outputPlayerNotLeavingPenaltyBox($x)
    {
        echoln($x . " is not getting out of the penalty box");
    }

    public static function outputPlayerLavingPenaltyBox($x)
    {
        echoln($x . " is getting out of the penalty box");
    }

    public static function outputCurrentPlayer($x)
    {
        echoln($x . " is the current player");
    }

    public static function outputRoll($roll)
    {
        echoln("They have rolled a " . $roll);
    }

    public static function outputPlayerNumber($x)
    {
        echoln("They are player number " . count($x));
    }

    public static function outputCorrectAnswer()
    {
        echoln("Answer was correct!!!!");
    }

    public static function outputPlayerBalance($x, $y)
    {
        echoln($x
            . " now has "
            . $y
            . " Gold Coins.");
    }

    public static function outputIncorrectAnswer()
    {
        echoln("Question was incorrectly answered");
    }

    public static function outputPlayerSentToPenaltyBox($x)
    {
        echoln($x . " was sent to the penalty box");
    }
}
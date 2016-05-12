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
}
<?php

include_once 'Game.php';
include_once 'OutputLayerConsole.php';


  $aGame = new Game();
  
  $aGame->addPlayer("Chet");
  $aGame->addPlayer("Pat");
  $aGame->addPlayer("Sue");



$aGame->errPlayGame();
  

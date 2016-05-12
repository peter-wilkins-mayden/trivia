<?php

include_once 'vendor/autoload.php';


  $aGame = new Game();
  
  $aGame->addPlayer("Chet");
  $aGame->addPlayer("Pat");
  $aGame->addPlayer("Sue");



$aGame->errPlayGame();
  

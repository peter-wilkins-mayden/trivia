<?php
function echoln($string) {
  echo $string."\n";
}

class Game {
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

	function  __construct(){
	$this->generateQuestions();
	}

	function add($playerName) {
	   array_push($this->players, $playerName);
	   $this->places[$this->howManyPlayers()] = 0;
	   $this->purses[$this->howManyPlayers()] = 0;
	   $this->inPenaltyBox[$this->howManyPlayers()] = false;

	    echoln($playerName . " was added");
	    echoln("They are player number " . count($this->players));
		return true;
	}

	function howManyPlayers() {
		return count($this->players);
	}

	function  roll($roll) {
		$this->outputCurrentPlayer();
		$this->outputRoll($roll);

		if ($this->playerIsInPenaltyBox()) {
			if ($this->rolledAnOddNumber($roll)) {
				$this->isGettingOutOfPenaltyBox = true;

				$this->outputPlayerLavingPenaltyBox();
				$this->moveAlongBoard($roll);
				if ($this->playerHasReachedEndOfBoard()) {
					$this->goBackToBeginningOfBoard();
				}

				$this->outputPlayerLocation();
				$this->outputCategory();
				$this->askQuestion();
			} else {
				$this->outputPlayerNotLeavingPenaltyBox();
				$this->isGettingOutOfPenaltyBox = false;
				}

		} else {

		$this->moveAlongBoard($roll);
			if ($this->playerHasReachedEndOfBoard()) {
				$this->goBackToBeginningOfBoard();
			}

			$this->outputPlayerLocation();
			$this->outputCategory();
			$this->askQuestion();
		}

	}

	function  askQuestion() {
		if ($this->currentCategory() == self::POP)
			echoln(array_shift($this->popQuestions));
		if ($this->currentCategory() == self::SCIENCE)
			echoln(array_shift($this->scienceQuestions));
		if ($this->currentCategory() == self::SPORTS)
			echoln(array_shift($this->sportsQuestions));
		if ($this->currentCategory() == self::ROCK)
			echoln(array_shift($this->rockQuestions));
	}


	function currentCategory() {
		return [self::POP, self::SCIENCE, self::SPORTS, self::ROCK][$this->places[$this->currentPlayer] % 4];
	}

	function wasCorrectlyAnswered() {
		if ($this->playerIsInPenaltyBox()){
			if ($this->isGettingOutOfPenaltyBox) {
				echoln("Answer was correct!!!!");
			$this->purses[$this->currentPlayer]++;
				echoln($this->players[$this->currentPlayer]
						. " now has "
						.$this->purses[$this->currentPlayer]
						. " Gold Coins.");

				$winner = $this->didPlayerWin();
				$this->currentPlayer++;
				if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;

				return $winner;
			} else {
				$this->currentPlayer++;
				if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
				return true;
			}



		} else {

			echoln("Answer was corrent!!!!");
		$this->purses[$this->currentPlayer]++;
			echoln($this->players[$this->currentPlayer]
					. " now has "
					.$this->purses[$this->currentPlayer]
					. " Gold Coins.");

			$winner = $this->didPlayerWin();
			$this->currentPlayer++;
			if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;

			return $winner;
		}
	}

	function wrongAnswer(){
		echoln("Question was incorrectly answered");
		echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
		$this->putPlayerIntoPenaltyBox();

		$this->currentPlayer++;
		if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
		return true;
	}


	function didPlayerWin() {
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
		return $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
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
		return echoln("The category is " . $this->currentCategory());
	}

	private function outputPlayerLocation()
	{
		return echoln($this->players[$this->currentPlayer]
			. "'s new location is "
			. $this->places[$this->currentPlayer]);
	}

	private function outputPlayerNotLeavingPenaltyBox()
	{
		return echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
	}

	private function outputPlayerLavingPenaltyBox()
	{
		return echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
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
}

<?php


class Questions
{
    private $popQuestions = [];
    private $scienceQuestions = [];
    private $sportsQuestions = [];
    private $rockQuestions = [];
    const POP = "Pop";
    const SCIENCE = "Science";
    const SPORTS = "Sports";
    const ROCK = "Rock";
    
    function __construct()
    {
        $this->generateQuestions();
    }

    public function askQuestion($place)
    {
        if ($this->currentCategory($place) == self::POP) {
            echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory($place) == self::SCIENCE) {
            echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory($place) == self::SPORTS) {
            echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory($place) == self::ROCK) {
            echoln(array_shift($this->rockQuestions));
        }
    }


    public function currentCategory($place)
    {
        return [self::POP, self::SCIENCE, self::SPORTS, self::ROCK][$place % 4];
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
}
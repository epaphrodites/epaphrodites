<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\modeleOne\bots\mainBot;
use Epaphrodites\epaphrodites\chatBot\modeleOne\bots\herediaBot;
use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\loadJson;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\analyzeWord;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\botAssembly;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\randomArray;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\languageWords;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\initVariables;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\cleanNormalize;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\dafaultAnswers;
use Epaphrodites\epaphrodites\chatBot\modeleOne\treatment\answersChecking;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\languageDetection;
use Epaphrodites\epaphrodites\chatBot\modeleOne\botConfig\jaccardCoefficient;

class chatBot {

use initVariables, loadJson, cleanNormalize, jaccardCoefficient, mainBot, herediaBot, dafaultAnswers, randomArray, languageDetection, botAssembly, analyzeWord, languageWords, answersChecking;

    /**
     * @param string $userMessage
     * @return array
     */
    protected function findResponse(string $userMessage):array
    {
       return $this->getResponse($userMessage);
    }

    /**
     * @param string $userMessage
     * @param string $botName
     * @return array
     */
    protected function findHerediaResponse(string $userMessage , string $botName):array
    {
       return $this->getHerediaResponse($userMessage , $botName);
    }    
}

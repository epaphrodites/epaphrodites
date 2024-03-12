<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\bots\mainBot;
use Epaphrodites\epaphrodites\chatBot\bots\herediaBot;
use Epaphrodites\epaphrodites\chatBot\loadSave\loadJson;
use Epaphrodites\epaphrodites\chatBot\botConfig\analyzeWord;
use Epaphrodites\epaphrodites\chatBot\botConfig\botAssembly;
use Epaphrodites\epaphrodites\chatBot\botConfig\randomArray;
use Epaphrodites\epaphrodites\chatBot\botConfig\languageWords;
use Epaphrodites\epaphrodites\chatBot\botConfig\initVariables;
use Epaphrodites\epaphrodites\chatBot\botConfig\cleanNormalize;
use Epaphrodites\epaphrodites\chatBot\botConfig\dafaultAnswers;
use Epaphrodites\epaphrodites\chatBot\treatment\answersChecking;
use Epaphrodites\epaphrodites\chatBot\botConfig\languageDetection;
use Epaphrodites\epaphrodites\chatBot\botConfig\jaccardCoefficient;

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

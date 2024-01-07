<?php

namespace Epaphrodites\epaphrodites\chatBot;

use Epaphrodites\epaphrodites\chatBot\loadSave\loadJson;
use Epaphrodites\epaphrodites\chatBot\botConfig\findResponse;
use Epaphrodites\epaphrodites\chatBot\botConfig\cleanNormalize;
use Epaphrodites\epaphrodites\chatBot\botConfig\dafaultAnswers;
use Epaphrodites\epaphrodites\chatBot\botConfig\herediaResponse;
use Epaphrodites\epaphrodites\chatBot\botConfig\jaccardCoefficient;

class chatBot {

use loadJson, cleanNormalize, jaccardCoefficient, findResponse, herediaResponse, dafaultAnswers;

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

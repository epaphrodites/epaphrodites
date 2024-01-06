<?php

namespace Epaphrodites\epaphrodites\chatBot;

class chatBot {

use loadJson, cleanNormalize, jaccardCoefficient, findResponse, herediaResponse;

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

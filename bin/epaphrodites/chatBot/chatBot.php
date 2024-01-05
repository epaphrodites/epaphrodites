<?php

namespace Epaphrodites\epaphrodites\chatBot;

class chatBot {

use loadJson, cleanNormalize, jaccardCoefficient, findResponse;

    /**
     * @param string $userMessage
     * @return array
     */
    protected function findResponse(string $userMessage):array
    {
       return $this->getResponse($userMessage);
    }
}

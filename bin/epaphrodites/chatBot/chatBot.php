<?php

namespace Epaphrodites\epaphrodites\chatBot;

class Chatbot {

use loadJson, cleanNormalize, jaccardCoefficient, findResponse;


    protected function findResponse(string $userMessage)
    {
        $this->getResponse($userMessage);
    }
}

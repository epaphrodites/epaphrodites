<?php

namespace Epaphrodites\epaphrodites\chatBot;

class Chatbot {

use loadJson, cleanNormalize, jaccardCoefficient, findResponse;

    public function findResponse(string $userMessage)
    {

        $this->getResponse($userMessage);
    }
}

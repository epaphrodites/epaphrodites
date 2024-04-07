<?php

namespace Epaphrodites\epaphrodites\chatBot\modelOne\makeActions;

use Epaphrodites\epaphrodites\chatBot\modelOne\loadSave\dropJson;
use Epaphrodites\epaphrodites\chatBot\modelOne\loadSave\loadJson;

class botActions{

use defaultActions, dropJson, loadJson;

    public function actions(
        string $actionName, 
        string $login, 
        string $jsonFile
    ):void
    {

       match ($actionName) {

            'clear' => $this->cleanJsonFile($login , $jsonFile),
            'init' => $this->cleanJsonFile($login , $jsonFile),
            default => null,
        };
    }
}
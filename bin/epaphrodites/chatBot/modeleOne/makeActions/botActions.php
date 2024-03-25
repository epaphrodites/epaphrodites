<?php

namespace Epaphrodites\epaphrodites\chatBot\modeleOne\makeActions;

use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\dropJson;
use Epaphrodites\epaphrodites\chatBot\modeleOne\loadSave\loadJson;

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
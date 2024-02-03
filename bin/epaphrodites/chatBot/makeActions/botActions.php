<?php

namespace Epaphrodites\epaphrodites\chatBot\makeActions;

use Epaphrodites\epaphrodites\chatBot\loadSave\dropJson;
use Epaphrodites\epaphrodites\chatBot\loadSave\loadJson;

class botActions{

use defaultActions, dropJson, loadJson;

    public function actions(string $actionName , string $login , string $jsonFile){

        match ($actionName) {

            'clear' => $this->cleanJsonFile($login , $jsonFile),
            'init' => $this->cleanJsonFile($login , $jsonFile),

            default => null,
        };
    }
}
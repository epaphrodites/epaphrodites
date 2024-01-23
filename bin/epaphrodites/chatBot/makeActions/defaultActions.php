<?php

namespace Epaphrodites\epaphrodites\chatBot\makeActions;

trait defaultActions{

    public function defaultActions(string $actionName , string $login){

        match ($actionName) {

            'clear' => $this->cleanJsonFile($login),

            default => null,
        };
    }
}
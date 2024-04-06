<?php

namespace Epaphrodites\epaphrodites\chatBot\modeleOne\makeActions;

trait defaultActions{

    public function defaultActions(
        string $actionName, 
        string $login
    ):void
    {
        match ($actionName) {

            'clear' => $this->cleanJsonFile($login),
            default => null,
        };
    }
}
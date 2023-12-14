<?php

namespace Epaphrodite\controllers\controllerMap;

use Epaphrodite\controllers\controllers\api;
use Epaphrodite\controllers\controllers\main;
use Epaphrodite\controllers\controllers\users;
use Epaphrodite\controllers\controllers\chats;
use Epaphrodite\controllers\controllers\setting;
use Epaphrodite\controllers\controllers\dashboard;

trait controllerMap
{

    /**
     * Returns an instance of the 'main' controller.
     *
     * @return object An instance of the 'main' controller.
     */
    private function mainController():object
    {
        return new main;
    }    

    /**
     * Returns an array mapping controllers to their respective instances and methods.
     *
     * @return array The mapping of controllers with their instances and methods.
     */
    private function controllerMap(): array
    {
        return [
            "api" => [ new api, 'SwitchApiControllers', false ],
            "chats" => [ new chats, 'SwitchControllers', true ],
            "users" => [ new users, 'SwitchControllers', true ],
            "setting" => [ new setting, 'SwitchControllers', true ],
            "dashboard" => [ new dashboard, 'SwitchControllers' , true ],
        ];
    }
}

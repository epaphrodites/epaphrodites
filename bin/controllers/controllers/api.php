<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\epaphrodites\heredia\HerediaApiSwitcher;
use Epaphrodites\epaphrodites\env\config\ResponseSequence;

final class api extends HerediaApiSwitcher
{

    protected object $Response;

    /**
     * Initialize object properties when an instance is created
     * 
     * @return void
     */    
    public final function __construct()
    {
        $this->initializeObjects();
    }

    /**
     * Initialize each property using values retrieved from static configurations
     * @return void
     */
    private function initializeObjects():void
    {
        $this->Response = new ResponseSequence;
    }      

    /**
     * All users list
     * 
     * @return array
     */
    public final function listeOfAllUsers()
    {

        $Result = [];
        $list = static::isGet('list') ? static::getGet('list') : 0;

        if (!empty($_GET['list'])) {

            return !empty($Result) ? $this->Response->JsonResponse(200, []) : $this->Response->JsonResponse(400, []);
        } else {

            return $this->Response->JsonResponse(200, $Result);
        }
    }  
}

<?php

namespace Epaphrodite\controllers\controllers;

use Epaphrodite\epaphrodite\heredia\HerediaApiSwitcher;
use Epaphrodite\epaphrodite\env\config\ResponseSequence;

final class api extends HerediaApiSwitcher
{

    protected object $Response;

    public function __construct()
    {
        $this->Response = new ResponseSequence;
    }

    /**
     * All users list
     * @return array
     */
    public function listeDesUtilisateurs()
    {

        $Result = [];
        $list = static::isGet('list') ? static::getGet('list') : 0;

        if (!empty($_GET['list'])) {

            return $Result == true ? $this->Response->JsonResponse(200, []) : $this->Response->JsonResponse(400, []);
        } else {

            return $this->Response->JsonResponse(400, $Result);
        }
    }
}

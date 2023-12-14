<?php

namespace Epaphrodite\controllers\switchers;

use Epaphrodite\epaphrodite\heredia\SwitchersHeredia;
use Epaphrodite\epaphrodite\define\config\traits\currentSubmit;

class MainSwitchers extends SwitchersHeredia
{

    use currentSubmit;

    /**
     * Rooter constructor
     *
     * @return \Epaphrodite\controllers\render\rooter
     */
    public static function rooter(): \Epaphrodite\controllers\render\rooter
    {
        return new \Epaphrodite\controllers\render\rooter;
    }
}

<?php

namespace Epaphrodites\controllers\switchers;

use Epaphrodites\epaphrodites\heredia\SwitchersHeredia;
use Epaphrodites\epaphrodites\define\config\traits\currentSubmit;

class MainSwitchers extends SwitchersHeredia
{

    use currentSubmit;

    /**
     * Rooter constructor
     *
     * @return \Epaphrodites\controllers\render\rooter
     */
    public static function rooter(): \Epaphrodites\controllers\render\rooter
    {
        return new \Epaphrodites\controllers\render\rooter;
    }
}

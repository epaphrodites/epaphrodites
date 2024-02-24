<?php

namespace Epaphrodites\epaphrodites\heredia;

use Epaphrodites\epaphrodites\yedidiah\Authorize;

class SwitchersHeredia extends Authorize{
    
    /**
     * @param string|null $target
     * @param string|null $url
     * @param null|string $autorize
     * @param bool $runner
     * @return bool|null
     */
    public static function SwitcherPages( ?string $target = null , ?string $url = null , ?bool $autorize = null , $Run=NULL ){

        if( static::target( $target , $url )===true ){

            $Run = $autorize === true ? static::Authorize($target) : true;
        }

        return $Run;
    }

    /**
     * @param mixed $target
     * @param mixed $url
     * @return bool
    */
    private static function target( $target , $url ){

        return $target . _MAIN_EXTENSION_ === $url ? true : false;
    }

    /**
     * @param string|null $target
     * @param null|string $autorize
     * @param bool $runner
     * @return bool|null
     */
    public function swicthPagesAutorisation( ?string $target = null , ?bool $autorize = null , $Run=NULL ){

        $isDashboard = strpos($target, _DASHBOARD_ ) !== false;

        $Run = $autorize === true && $isDashboard === false ? static::Authorize($target) : true;

        return $Run;
    }    
}


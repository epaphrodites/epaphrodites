<?php

namespace Epaphrodites\epaphrodites\yedidiah;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class Authorize extends epaphroditeClass
{

    /**
     * @return bool
    */
    private static function checkAuthorization($pages):bool
    {
        $actions = false;
        $JsonDatas = file_get_contents(static::JsonDatas());
        $pages = str_replace( _MAIN_EXTENSION_ , '', $pages);

        $index = md5(static::class('session')->type() . ',' . $pages);
        $jsonFileDatas = json_decode($JsonDatas, true);
       
        foreach ($jsonFileDatas as $key => $value) {

            if ($value['IndexRight'] == $index) {
                $actions = $value['Autorisations'] == 1 ? true : false;
            }
        }

        return $actions;        
    }

    /**
     * @return bool
    */
    public static function Authorize($pages):bool
    {
        $action = true;

        if(static::class('session')->type()!==1){ 
            $action = static::checkAuthorization($pages) === true ? true : static::class('errors')->error_403(); 
        }

        return $action;
    }
}
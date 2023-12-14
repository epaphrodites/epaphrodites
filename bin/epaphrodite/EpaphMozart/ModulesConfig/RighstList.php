<?php

namespace Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig;

use Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\Lists\GetRightList;

class RighstList extends GetRightList
{

    /**
     * Liste des contenus des menus de l'application
     * @param int $key
     * @return array
     */
    public function YedidiahRightList(?string $key = null, ?string $value = null)
    {

        $paths = static::RightList();
           
        if ($key === null) {
            return $paths;
        } else {
            return $paths[$key][$value];
        }
    }

}
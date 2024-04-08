<?php

namespace Epaphrodites\epaphrodites\env\json\requests;

trait updJson
{

    /**
     * @param array $JsonDatas
     * @param string $usersGroup
     * @param int $state
     * @return bool
     */
    private function updateUsersRightsDatas(
        array $JsonDatas,
        string $usersGroup, 
        int $state
    ):bool{

        $hasChanges = false;

        foreach ($JsonDatas as $key => $value) {

            if (is_array($value) && $value['indexRight'] == $usersGroup) {
                $JsonDatas[$key]['Autorisations'] = $state;
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            static::saveJson($JsonDatas);
        }

        return $hasChanges;
    }  
}
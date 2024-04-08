<?php

namespace Epaphrodites\epaphrodites\env\json\requests;

trait addToJson
{

    /**
     * @param string $jsonFile
     * @param array $datas
     * @return bool
     */
    private function saveUsersRights(
        string $jsonFile,
        array $datas = []
    ):bool{

        $JsonDatas = !empty(file_get_contents(static::JsonDatas())) ? file_get_contents($jsonFile) : "[]";

        if ($JsonDatas !== false) {
            $JsonDatas = json_decode($JsonDatas, true);            
        }

        $JsonDatas[] = $datas;            

        static::saveJson($JsonDatas);       
        
        return true;
    }
}
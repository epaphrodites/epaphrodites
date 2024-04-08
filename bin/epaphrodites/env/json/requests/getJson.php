<?php

namespace Epaphrodites\epaphrodites\env\json\requests;

trait getJson
{

    /**
     * Request to select user right if exist
     * @param string $usersGroup
     * @param string $pages
     * @param array $getJsonArray
     * @return bool
     */
    private function all(
        string $usersGroup, 
        string $pages, 
        array $getJsonArray
    ): bool{
        
        $hasAccess = false;
    
        if (!empty($getJsonArray)) {

            $index = md5($usersGroup . ',' . $pages);

            foreach ($getJsonArray as $value) {

                if ($value['indexRight'] == $index) {
                    $hasAccess = true;
                    break;
                }
            }
        }
    
        return $hasAccess;
    }   
    
    /**
     * Request to select user rights by user type.
     * @param int $idUserGroup
     * @return array
     */
    private function select(
        int $idUserGroup
    ): array{

        $result = [];
        $jsonFileDatas = static::loadJsonFile();

       foreach ($jsonFileDatas as $key => $value) {
           
            if (is_array($value) && $value['usersRightsGroup'] == $idUserGroup) {
                $result[] = $value;
            }
        }

        return $result;
    }   
}
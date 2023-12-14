<?php

namespace Epaphrodites\epaphrodites\yedidiah;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class YedidiaGetRights extends epaphroditeClass{

    /**
     * Request to select user right by module and user type.
     *
     * @param string|null $module
     * @return bool
     */
    public function modules(string $module = null): bool
    {
        $result = false;
        $index = $module . ',' . static::class('session')->type();

        $json_arr = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($json_arr as $key => $value) {
            if ($value['IndexModule'] == $index) {
                $result = true;
                break;
            }
        }

        return $result;
    }

   /**
     * Request to select user rights by user type.
     *
     * @param int $idUserGroup
     * @return array
     */
    private function showYediadiahRights(int $idUserGroup): array
    {

        $result = [];
        $json_arr = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($json_arr as $key => $value) {
            if ($value['IdtypeUserRights'] == $idUserGroup) {
                $result[] = $json_arr[$key];
            }
        }

        return $result;
    }

   /**
     * Request to select user rights by user type and key.
     *
     * @param string|null $key
     * @return array
     */
    public function liste_menu(?string $key = null): array
    {

        $result = [];
        $index = $key . ',' . static::class('session')->type();

        $json_arr = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($json_arr as $key => $value) {
            if ($value['IndexModule'] === $index) {
                $result[] = $json_arr[$key];
            }
        }

        return $result;
    }

    /**
     * @param int $idUserGroup
     * @return array
     */
    public function getUsersRights(int $idUserGroup){

        return $this->showYediadiahRights($idUserGroup);
    }
}
<?php

namespace Epaphrodites\epaphrodites\yedidiah;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class YedidiaDeleted extends epaphroditeClass{

    /**
     * Request to delete users right by @id
     *
     * @param int $idRights
     * @return bool
     */
    public function DeletedUsersRights($idRights):bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {
            if ($value['IndexRight'] == $idRights) {
                unset($JsonDatas[$key]);
            }
        }

        file_put_contents(static::JsonDatas(), json_encode($JsonDatas));
        return true;
    }

    /**
     * Request to delete all users right by users type
     *
     * @param int $typeUsers
     * @return bool
     */
    public function EmptyAllUsersRight($typeUsers):bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {
            if ($value['IdtypeUserRights'] == $typeUsers) {
                unset($JsonDatas[$key]);
            }
        }

        file_put_contents(static::JsonDatas(), json_encode($JsonDatas));
        return true;
    }    

}

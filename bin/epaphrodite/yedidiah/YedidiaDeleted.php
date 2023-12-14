<?php

namespace Epaphrodite\epaphrodite\yedidiah;

use Epaphrodite\epaphrodite\constant\epaphroditeClass;

class YedidiaDeleted extends epaphroditeClass{

    /**
     * Request to delete users right by @id
     *
     * @param int $idRights
     * @return bool
     */
    public function DeletedUsersRights($IdRights):bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {
            if ($value['IndexRight'] == $IdRights) {
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
    public function EmptyAllUsersRight($TypeUsers):bool
    {

        $JsonDatas = json_decode(file_get_contents(static::JsonDatas()), true);

        foreach ($JsonDatas as $key => $value) {
            if ($value['IdtypeUserRights'] == $TypeUsers) {
                unset($JsonDatas[$key]);
            }
        }

        file_put_contents(static::JsonDatas(), json_encode($JsonDatas));
        return true;
    }    

}

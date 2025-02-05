<?php

namespace Epaphrodites\database\requests\mainRequest\delete;

use Epaphrodites\database\requests\typeRequest\sqlRequest\delete\delete as DeleteDelete;

final class delete extends DeleteDelete
{

    /**
     * Request to delete users right by @id
     * 
     * @param int $idRights
     * @return bool
    */
    public function DeletedUsersRights(
        string $idRights
    ):bool{

        return  static::initConfig()['delright']->DeletedUsersRights($idRights) === true ? true : false;
    }

    /**
     * Request to delete users right by @id
     * 
     * @param int $usersGroup
     * @return bool
    */
    public function EmptyAllUsersRights(
        int $usersGroup
    ):bool{

        return  static::initConfig()['delright']->EmptyAllUsersRight($usersGroup) === true ? true : false;
    }    

}
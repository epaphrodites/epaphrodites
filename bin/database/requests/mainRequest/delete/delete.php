<?php

namespace Epaphrodite\database\requests\mainRequest\delete;

use Epaphrodite\database\requests\typeRequest\sqlRequest\delete\delete as DeleteDelete;

final class delete extends DeleteDelete
{

    /**
     * Request to delete users right by @id
     */
    public function DeletedUsersRights($IdRights)
    {

        return  static::initConfig()['delright']->DeletedUsersRights($IdRights)===true ? true : false;
    }

    /**
     * Request to delete users right by @id
     */
    public function EmptyAllUsersRights($TypeUsers)
    {

        return  static::initConfig()['delright']->EmptyAllUsersRight($TypeUsers)===true ? true : false;
    }    

}
<?php

namespace Epaphrodite\database\requests\mainRequest\select;

use Epaphrodite\database\requests\typeRequest\sqlRequest\select\get_id as GetId;

final class get_id extends GetId
{

    /**
     * Request to select user right by module and 
     * 
     * @param string|null $module
     */
    public function GetModules(?string $module = null):array
    {

        return static::initConfig()['listright']->modules($module);
    }

    /**
     * Request to select user right by user type
     */
    public function getUsersRights($idtype_user):array
    {

        return static::initConfig()['listright']->getUsersRights($idtype_user);
    }

    /**
     * Request to select user right by user type
     * @param string|null $key
     * @return array
     */
    public function liste_menu(?string $key = null):array
    {

        return static::initConfig()['listright']->liste_menu($key);
    }  

  /**
   * Request to check users by login
   *
   * @param string $loginuser
   * @return array
   */
  public function GetUsersDatas(?string $login = null):array
  {

    return $this->checkDbType() === true ? $this->sqlGetUsersDatas($login) : $this->noSqlGetUsersDatas($login);
  }

  /**
   * Request to check users per group
   *
   * @param string $loginuser
   * @return array
   */
  public function GetUsersByGroup(int $page, int $Nbreligne, int $UsersGroup):array
  {

    return $this->checkDbType() === true ? $this->sqlGetUsersByGroup($page , $Nbreligne , $UsersGroup) : $this->noSqlGetUsersByGroup($page , $Nbreligne , $UsersGroup);
  }

  /**
   * Request to select users actions list by login
   *
   * @param string $loginuser
   * @return array
   */
  public function getUsersRecentsActions(?string $login = null):array
  {

    return $this->checkDbType() === true ? $this->sqlGetUsersRecentsActions($login) : $this->noSqlGetUsersRecentsActions($login);
  }

}
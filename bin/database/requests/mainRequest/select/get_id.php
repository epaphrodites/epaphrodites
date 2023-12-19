<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\get_id as GetId;

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

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersDatas($login),
      'redis' => $this->noSqlRedisGetUsersDatas($login),

      default => $this->sqlGetUsersDatas($login),
    };    
  }

  /**
   * Request to check users per group
   *
   * @param string $loginuser
   * @return array
   */
  public function GetUsersByGroup(int $page, int $Nbreligne, int $UsersGroup):array
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersByGroup($page , $Nbreligne , $UsersGroup),
      'redis' => $this->noSqlGetUsersByGroup($page , $Nbreligne , $UsersGroup),

      default => $this->sqlGetUsersByGroup($page , $Nbreligne , $UsersGroup),
    };        
    
  }

  /**
   * Request to select users actions list by login
   *
   * @param string $loginuser
   * @return array
   */
  public function getUsersRecentsActions(?string $login = null):array
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersRecentsActions($login),
      'redis' => $this->noSqlGetUsersRecentsActions($login),

      default => $this->sqlGetUsersRecentsActions($login),
    };      
  }

}
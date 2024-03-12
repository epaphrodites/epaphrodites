<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\get_id as GetId;

final class get_id extends GetId
{


    /**
     * Request to select user right by user usersGroup
     * 
     * @param int $usersGroup
     * @return array
     */
    public function getUsersRights(
      int $usersGroup
    ):array{

        return static::initConfig()['listright']->getUsersRights($usersGroup);
    }

  /**
   * Request to check users by login
   * 
   * @param string $login
   * @return array
   */
  public function GetUsersDatas(
    string $login
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersDatas($login),
      'redis' => $this->noSqlRedisGetUsersDatas($login),

      default => $this->sqlGetUsersDatas($login),
    };    
  }

  /**
   * Request to check users per group
   *
   * @param int $page
   * @param int $numLine
   * @param int $UsersGroup
   * @return array
   */
  public function GetUsersByGroup(
    int $page, 
    int $numLine, 
    int $UsersGroup
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersByGroup($page , $numLine , $UsersGroup),
      'redis' => $this->noSqlGetUsersByGroup($page , $numLine , $UsersGroup),

      default => $this->sqlGetUsersByGroup($page , $numLine , $UsersGroup),
    };        
  }

  /**
   * Request to select users actions list by login
   * 
   * @param string|null $login
   * @return array
   */
  public function getUsersRecentsActions(
    ?string $login = null
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlGetUsersRecentsActions($login),
      'redis' => $this->noSqlGetUsersRecentsActions($login),

      default => $this->sqlGetUsersRecentsActions($login),
    };      
  }
}
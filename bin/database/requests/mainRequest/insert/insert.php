<?php

namespace Epaphrodites\database\requests\mainRequest\insert;

use Epaphrodites\database\requests\typeRequest\sqlRequest\insert\insert as InsertInsert;

final class insert extends InsertInsert
{

    /**
     * Add users rights
     * @param int|null $userGroup
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(
      ?int $userGroup = null, 
      ?string $pages = null, 
      ?string  $actions = null
    ):bool{

        if (static::initConfig()['addright']->AddUsersRights($userGroup, $pages, $actions) === true) {

            $actions = "Assign a right to the user group : " . static::initNamespace()['datas']->userGroup($userGroup);
            static::initQuery()['setting']->ActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }  

  /**
   * Add a new users
   * @param string $login
   * @param int $userGroup
   * @return bool
   */
  public function addUsers(
    ?string $login, 
    ?int $userGroup
  ):bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqladdUsers($login , $userGroup),
      'redis' => $this->noSqladdUsers($login , $userGroup),

      default => $this->sqlAddUsers($login , $userGroup),
    };    
  } 
  
   /**
   * Add a new users from console
   * @param string $login
   * @param string $password
   * @param int $UserGroup
   * @return array
   */
  public function ConsoleAddUsers(
    ?string $login,
    ?string $password,
    ?int $UserGroup
  ):bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlConsoleAddUsers($login , $password, $UserGroup),
      'redis' => $this->noSqlConsoleAddUsers($login , $password, $UserGroup),

      default => $this->sqlConsoleAddUsers($login , $password, $UserGroup),
    };     
  }   
 }
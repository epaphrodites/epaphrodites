<?php

namespace Epaphrodites\database\requests\mainRequest\insert;

use Epaphrodites\database\requests\typeRequest\sqlRequest\insert\insert as InsertInsert;

final class insert extends InsertInsert
{

    /**
     * Add users rights
     * index ( module , type_user , idpage , action)
     * @param int|null $idtype_users
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(?int $idUsersGroup = null, ?string $pages = null,  ?string  $actions = null)
    {

        if (static::initConfig()['addright']->AddUsersRights($idUsersGroup, $pages, $actions) === true) {

            $actions = "Assign a right to the user group : " . static::initNamespace()['datas']->userGroup($idUsersGroup);
            static::initQuery()['setting']->ActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }  

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return array
   */
  public function addUsers(?string $login = null, ?int $idtype = null):bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqladdUsers($login , $idtype),
      'redis' => $this->noSqladdUsers($login , $idtype),

      default => $this->sqlAddUsers($login , $idtype),
    };    
  } 
  
   /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return array
   */
  public function ConsoleAddUsers(?string $login = null, ?string $password = null, ?int $UserGroup = null): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlConsoleAddUsers($login , $password, $UserGroup),
      'redis' => $this->noSqlConsoleAddUsers($login , $password, $UserGroup),

      default => $this->sqlConsoleAddUsers($login , $password, $UserGroup),
    };     
  }   
 }
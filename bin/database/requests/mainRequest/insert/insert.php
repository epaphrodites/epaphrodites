<?php

namespace Epaphrodites\database\requests\mainRequest\insert;

use Epaphrodites\database\requests\typeRequest\sqlRequest\insert\insert as InsertInsert;

final class insert extends InsertInsert
{

    /**
     * Ajouter des droits utilisateurs
     * index ( module , type_user , idpage , action)
     * @param int|null $idtype_users
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(?int $idtypeUsers = null, ?string $pages = null,  ?string  $actions = null)
    {

        if (static::initConfig()['addright']->AddUsersRights($idtypeUsers, $pages, $actions) === true) {

            $actions = "Assign a right to the user group : " . static::initNamespace()['datas']->userGroup($idtypeUsers);
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

    return $this->checkDbType() === true ? $this->sqlAddUsers($login , $idtype) : $this->noSqladdUsers($login , $idtype);
  } 
  
   /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return array
   */
  public function ConsoleAddUsers(?string $login = null, ?string $password = null, ?int $UserGroup = null): bool
  {

    return $this->checkDbType() === true ? $this->sqlConsoleAddUsers($login , $password, $UserGroup) : $this->noSqlConsoleAddUsers($login , $password, $UserGroup);
  }   

 }
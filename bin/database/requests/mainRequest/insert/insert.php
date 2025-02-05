<?php

namespace Epaphrodites\database\requests\mainRequest\insert;

use Epaphrodites\database\requests\typeRequest\sqlRequest\insert\insert as InsertInsert;

final class insert extends InsertInsert
{

    /**
     * Add users rights
     * 
     * @param int|null $userGroup
     * @param string|null $pages
     * @param string|null $actions
     * @return bool
     */
    public function AddUsersRights(
      int|null $userGroup = null, 
      string|null $pages = null, 
      string|null  $actions = null
    ):bool{

      if (static::initConfig()['addright']->AddUsersRights($userGroup, $pages, $actions) === true) {
        
            $config = static::initQuery()['setting'];
            $actions = "Assign a right to the user group : " . static::initNamespace()['datas']->userGroup($userGroup);

            match (_FIRST_DRIVER_) {

              'mongodb' => $config->noSqlActionsRecente($actions),
              'redis' => $config->noSqlRedisActionsRecente($actions),
        
              default => $config->ActionsRecente($actions),
            };

            return true;
        } else {
            return false;
        }
    }  

    /**
     * Set user dashboard color
     * 
     * @param int $usersGroup
     * @param string $color
     * @return bool
     */
    public function setDashboardColors(
      int $usersGroup, 
      string $color
    ): bool{
        if (empty($usersGroup) || empty($color)) {
            return false;
        }

        $json = static::initNamespace()['json'];
        $path = $json->path(_DIR_COLORS_PATH_);

        $existingEntry = $path->get(['usersGroup' => $usersGroup]);

        if (!$existingEntry) {
            $path->add(['usersGroup' => $usersGroup, 'color' => $color]);
        } else {
            $path->where(['usersGroup' => $usersGroup])->update(['color' => $color]);
        }

        return true;
    }  

  /**
   * Add a new users
   * @param string $login
   * @param int $userGroup
   * @return bool
   */
  public function addUsers(
    string $login, 
    int $userGroup
  ):bool{

    return match (_FIRST_DRIVER_) {

      'mongodb' => $this->noSqladdUsers($login , $userGroup),
      'redis' => $this->noSqlRedisAddUsers($login , $userGroup),

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
    string $login,
    string $password,
    int $UserGroup
  ):bool{

    return match (_FIRST_DRIVER_) {

      'mongodb' => $this->noSqlConsoleAddUsers($login , $password, $UserGroup),
      'redis' => $this->noSqlRedisConsoleAddUsers($login , $password, $UserGroup),

      default => $this->sqlConsoleAddUsers($login , $password, $UserGroup),
    };     
  }  

 }
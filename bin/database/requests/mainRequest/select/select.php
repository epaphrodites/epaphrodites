<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\select as SelectSelect;

final class select extends SelectSelect
{

  public function selectUsersColors(): array
  {
      $namespace = static::initNamespace();
      $json = $namespace['json'];
      $datas = $namespace['datas'];
  
      $groupColors = array_column(
          $json->path(_DIR_COLORS_PATH_)->get(),
          'color',
          'usersGroup'
      );
  
      return array_map(
          fn(string $groupName, string|int $groupId): array => [
              'usersGroup' => $groupId,
              'color' => $groupColors[$groupId] ?? '',
              'groupName' => $groupName
          ],
          $datas->userGroup(),
          array_keys($datas->userGroup())
      );
  }


  /**
   * Request to get users list
   * @param int $currentPage
   * @param int $numLine
   * @return array
   */
  public function listeOfAllUsers(
    int $currentPage, 
    int $numLine
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongodb' => $this->noSqlListeOfAllUsers($currentPage, $numLine),
      'redis' => $this->noSqlRedisListeOfAllUsers($currentPage, $numLine),
      'sqlserver' => $this->sqlServerListeOfAllUsers( $currentPage, $numLine),
      'oracle' => $this->oracleListeOfAllUsers( $currentPage, $numLine),

      default => $this->defaultSqlListeOfAllUsers($currentPage, $numLine),
    };       
  }  
  
  /**
   * Request to get list of users recents actions
   * @param int $currentPage
   * @param int $numLine
   * @return array
   */
  public function listOfRecentActions(
    int $currentPage, 
    int $numLine
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongodb' => $this->noSqlListOfRecentActions($currentPage, $numLine),
      'redis' => $this->noSqlRedisListOfRecentActions($currentPage, $numLine),
      'oracle' => $this->oracleListOfRecentActions($currentPage, $numLine),
      'sqlserver' => $this->sqlServerListOfRecentActions($currentPage, $numLine),

      default => $this->defaultSqlListOfRecentActions($currentPage, $numLine),
    };           
  }   
}
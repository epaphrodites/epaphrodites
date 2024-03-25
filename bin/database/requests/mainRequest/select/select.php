<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\select as SelectSelect;

final class select extends SelectSelect
{

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

      'mongo' => $this->noSqlListeOfAllUsers($currentPage, $numLine),
      'redis' => $this->noSqlRedisListeOfAllUsers($currentPage, $numLine),
      'sqlserver' => $this->sqlServerListeOfAllUsers( $currentPage, $numLine),

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

      'mongo' => $this->noSqlListOfRecentActions($currentPage, $numLine),
      'redis' => $this->noSqlRedisListOfRecentActions($currentPage, $numLine),

      default => $this->sqlListOfRecentActions($currentPage, $numLine),
    };           
  }   
}
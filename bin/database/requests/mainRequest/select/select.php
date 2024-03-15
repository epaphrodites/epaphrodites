<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\select as SelectSelect;

final class select extends SelectSelect
{

  /**
   * Request to get users list
   * @param int $page
   * @param int $numLine
   * @return array
   */
  public function listeOfAllUsers(
    ?int $page, 
    int $numLine
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlListeOfAllUsers($page, $numLine),
      'redis' => $this->noSqlRedisListeOfAllUsers($page, $numLine),
      'sqlserver' => $this->sqlServerListeOfAllUsers( $page, $numLine),

      default => $this->defaultSqlListeOfAllUsers($page, $numLine),
    };       
  }  
  
  /**
   * Request to get list of users recents actions
   * @param int $page
   * @param int $numLine
   * @return array
   */
  public function listOfRecentActions(
    int $page, 
    int $numLine
  ):array{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlListOfRecentActions($page, $numLine),
      'redis' => $this->noSqlRedisListOfRecentActions($page, $numLine),

      default => $this->sqlListOfRecentActions($page, $numLine),
    };           
  }   
}
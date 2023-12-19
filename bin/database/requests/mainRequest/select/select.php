<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\select as SelectSelect;

final class select extends SelectSelect
{

  /**
   * Request to get users list
   *
   * @param int $page
   * @param int $nbreLigne
   * @return array
   */
  public function listeOfAllUsers(int $page, int $nbreLigne):array
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlListeOfAllUsers($page,$nbreLigne),
      'redis' => $this->noSqlRedisListeOfAllUsers($page,$nbreLigne),

      default => $this->sqlListeOfAllUsers($page,$nbreLigne),
    };       
  }  
  
  /**
   * Request to get list of users recents actions
   *
   * @param int $page
   * @param int $nbreLigne
   * @return array
   */
  public function listOfRecentActions(int $page, int $nbreLigne):array
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlListOfRecentActions($page,$nbreLigne),
      'redis' => $this->noSqlRedisListOfRecentActions($page,$nbreLigne),

      default => $this->sqlListOfRecentActions($page,$nbreLigne),
    };           
  }   

}
<?php

namespace Epaphrodite\database\requests\mainRequest\select;

use Epaphrodite\database\requests\typeRequest\sqlRequest\select\select as SelectSelect;

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

    return $this->checkDbType() === true ? $this->sqlListeOfAllUsers($page,$nbreLigne) : $this->noSqlListeOfAllUsers($page,$nbreLigne);
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

    return $this->checkDbType() === true ? $this->sqlListOfRecentActions($page,$nbreLigne) : $this->noSqlListOfRecentActions($page,$nbreLigne);
  }   

}
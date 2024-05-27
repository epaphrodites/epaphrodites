<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\count as SelectCount;

class count extends SelectCount
{

  /**
   * Get total users number
   * @return int
   */
  public function sqlCountAllUsers(): int
  {
    $result = $this->table('usersaccount')->SQuery("COUNT(*) AS NBRE");

    return $result[0]['NBRE'];
  }

  /** 
   * Get total number of user bd
   * @param int $Group
   * @return int
   */
  public function sqlCountUsersByGroup(
    int $Group
  ): int
  {
    $result = $this->table('usersaccount')
                  ->where('usersgroup')
                  ->param([$Group])
                  ->SQuery("COUNT(*) AS NBRE");

    return $result[0]['NBRE'];
  }

  /** 
   * Get total number of user bd
   * @param int $Group
   * @return int
   */
  public function sqlCountUsersRecentActions(): int
  {
    $result = $this->table('history')
      ->SQuery("COUNT(*) AS NBRE");

    return $result[0]['NBRE'];
  }     
}

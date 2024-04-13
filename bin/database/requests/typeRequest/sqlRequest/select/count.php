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
    $result = $this->table('useraccount')->SQuery("COUNT(*) AS nbre");

    return $result[0]['nbre'];
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
    $result = $this->table('useraccount')
                  ->where('usersgroup')
                  ->param([$Group])
                  ->SQuery("COUNT(*) AS nbre");

    return $result[0]['nbre'];
  }

  /** 
   * Get total number of user bd
   * @param int $Group
   * @return int
   */
  public function sqlCountUsersRecentActions(): int
  {
    $result = $this->table('recentactions')
      ->SQuery("COUNT(*) AS nbre");

    return $result[0]['nbre'];
  }  

  /**
   * Get total users number
   * @return int
   */
  public function sqlCountTransactionPerOperotor(): array
  {
    $result = $this->table('transactions')
                   ->sdb(2)
                   ->groupBy('operator')
                   ->SQuery("COUNT(operator) AS nbre, operator");

    return $result;
  }    
}

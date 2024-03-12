<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\count as SelectCount;

class count extends SelectCount
{

  /**
   * request to count users messages
   *
   * @param integer session logon
   * @return int
   */
  public function sqlChatMessages(): int
  {

    $login = static::initNamespace()['session']->login();

    $result = $this->table('chatsmessages')
                  ->like('destinataire')
                  ->and(['etatmessages'])
                  ->param([$login, 1])
                  ->SQuery('COUNT(*) AS nbre');

    return $result[0]['nbre'];
  }

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
  public function sqlCountUsersByGroup(int $Group): int
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

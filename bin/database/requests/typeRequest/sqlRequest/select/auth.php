<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\select;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\select\auth as SelectAuth;

class auth extends SelectAuth
{

  /**
   * Verify if useraccount table exist in database (For mysql/postgresql)
   * @return bool
   */
  protected function if_table_exist(): bool
  {

    try {

      $this->table('useraccount')->SQuery();

      return true;
    } catch (\Exception $e) {

      return false;
    }
  }

  /**
   * Request to select all users of database (For mysql/postgresql)
   * 
   * @param string $login
   * @return array|bool
   */
  public function findSqlUsers(
    string $login
  ):array|bool{

    if ($this->if_table_exist() === true) {

      $result = $this->table('useraccount')
          ->like('loginusers')
          ->param([$login])
          ->SQuery();

      return $result;
    } else {

      static::firstSeederGeneration();

      return false;
    }
  }
}

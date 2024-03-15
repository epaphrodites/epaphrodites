<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\auth as SelectAuth;

final class auth extends SelectAuth
{

  /**
   * Check users
   * @param string $login
   * @return array|bool
  */
  public function checkUsers(
    string $login
  ):array|bool{

    return match (_FIRST_DRIVER_) {

          'mongo' => $this->findNosqlUsers($login),
          'redis' => $this->findNosqlRedisUsers($login),

          default => $this->findSqlUsers($login),
    };
  }
}

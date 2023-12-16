<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\auth as SelectAuth;

final class auth extends SelectAuth
{

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return array
   */
  public function checkUsers(string $loginuser)
  {

    return match (_FIRST_DRIVER_) {

          'mongo' => $this->findNosqlUsers($loginuser),
          'redis' => $this->findNosqlRedisUsers($loginuser),

          default => $this->findSqlUsers($loginuser),
    };
  }
}

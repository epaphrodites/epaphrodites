<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\general as GeneralGeneral;

final class general extends GeneralGeneral
{

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return array
   */
  public function RecentlyActions(): array
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlRecentlyActions(),
      'redis' => $this->noSqlRedisRecentlyActions(),

      default => $this->sqlRecentlyActions(),
    };
  }
}

<?php

namespace Epaphrodites\database\requests\mainRequest\select;

use Epaphrodites\database\requests\typeRequest\sqlRequest\select\count as CountCount;

final class count extends CountCount
{

  /**
   * Request to count all users messages
   * @return array
   */
  public function chat_messages(): int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlchatMessages(),
      'redis' => $this->noSqlchatMessages(),

      default => $this->sqlChatMessages(),
    };
  }

  /**
   * Request to count all users
   * @return array
   */
  public function CountAllUsers(): int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlCountAllUsers(),
      'redis' => $this->noSqlCountAllUsers(),

      default => $this->sqlCountAllUsers(),
    };
  }

  /**
   * Request to count all users per group
   * @param string $loginuser
   * @return array
   */
  public function CountUsersByGroup(int $Group): int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlCountUsersByGroup($Group),
      'redis' => $this->noSqlCountUsersByGroup($Group),

      default => $this->sqlCountUsersByGroup($Group),
    };
  }

  /**
   * Request to count all users per group
   * @param string $loginuser
   * @return array
   */
  public function countUsersRecentActions(): int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlCountUsersRecentActions(),
      'redis' => $this->noSqlCountUsersRecentActions(),

      default => $this->sqlCountUsersRecentActions(),
    };
  }
}

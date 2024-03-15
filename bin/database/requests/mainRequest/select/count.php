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

      'mongo' => $this->noSqlChatMessages(),
      'redis' => $this->noSqlRedisChatMessages(),

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
      'redis' => $this->noSqlRedisCountAllUsers(),

      default => $this->sqlCountAllUsers(),
    };
  }

  /**
   * Request to count all users per group
   * @param string $usersGroup
   * @return array
   */
  public function CountUsersByGroup(
    int $usersGroup
  ):int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlCountUsersByGroup($usersGroup),
      'redis' => $this->noSqlRedisCountUsersByGroup($usersGroup),

      default => $this->sqlCountUsersByGroup($usersGroup),
    };
  }

  /**
   * Request to count all recent actions
   * @return array
   */
  public function countUsersRecentActions(): int
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlCountUsersRecentActions(),
      'redis' => $this->noSqlRedisCountUsersRecentActions(),

      default => $this->sqlCountUsersRecentActions(),
    };
  }
}
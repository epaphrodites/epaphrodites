<?php

namespace Epaphrodite\database\requests\mainRequest\select;

use Epaphrodite\database\requests\typeRequest\sqlRequest\select\count as CountCount;

final class count extends CountCount
{

  /**
   * Request to count all users messages
   * @return array
   */
  public function chat_messages(): int
  {

    return $this->checkDbType() === true ? $this->sqlChatMessages() : $this->noSqlchatMessages();
  }

  /**
   * Request to count all users
   * @return array
   */
  public function CountAllUsers():int
  {

    return $this->checkDbType() === true ? $this->sqlCountAllUsers() : $this->noSqlCountAllUsers();
  } 
  
  /**
   * Request to count all users per group
   * @param string $loginuser
   * @return array
   */
  public function CountUsersByGroup(int $Group ):int
  {

    return $this->checkDbType() === true ? $this->sqlCountUsersByGroup($Group) : $this->noSqlCountUsersByGroup($Group);
  }

  /**
   * Request to count all users per group
   * @param string $loginuser
   * @return array
   */
  public function countUsersRecentActions():int
  {

    return $this->checkDbType() === true ? $this->sqlCountUsersRecentActions() : $this->noSqlCountUsersRecentActions();
  }  
 
 }
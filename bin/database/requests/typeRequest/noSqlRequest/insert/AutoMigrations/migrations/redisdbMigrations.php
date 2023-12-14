<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\migrations;

trait redisdbMigrations{

/**
   * Create user if not exist
   */
  private function CreateRedisDbUserIfNotExist()
  {

    $this->db(1)->createCollection("useraccount");
  }

  /**
   * Create recently users actions if not exist
   */
  private function createRecentlyActionsRedisDbIfNotExist()
  {

    $this->db(1)->createCollection('recentactions');
  }

  /**
   * Create auth_secure if not exist
   */
  private function CreateAuthSecureRedisDbIfNotExist()
  {

    $this->db(1)->createCollection('authsecure');
  }

  /**
   * Create messages if not exist
   */
  private function CreateChatMessagesRedisDbIfNotExist()
  {

    $this->db(1)->createCollection('chatsmessages');
  }     

}
<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\migrations;

trait mongodbMigrations{

/**
   * Create user if not exist
   */
  private function CreateMongoUserIfNotExist()
  {

    $this->db(1)->createCollection("useraccount");
  }

  /**
   * Create recently users actions if not exist
   */
  private function createRecentlyActionsMongoIfNotExist()
  {

    $this->db(1)->createCollection('recentactions');
  }

  /**
   * Create auth_secure if not exist
   */
  private function CreateAuthSecureMongoIfNotExist()
  {

    $this->db(1)->createCollection('authsecure');
  }

  /**
   * Create messages if not exist
   */
  private function CreateChatMessagesMongoIfNotExist()
  {

    $this->db(1)->createCollection('chatsmessages');
  }    

}
<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\seeders;

use MongoDB\BSON\ObjectId;

trait noSqlSeeders{

  /**
   * Create user if not exist
   */
  private function CreateFirstUserIfNotExist()
  {
    
    $document =[
      'idusers' => new ObjectId,
      'login' => 'admin',
      'password' => $this->Guard->CryptPassword('admin'),
      'namesurname' => NULL,
      'contact' => NULL,
      'email' => NULL,
      'usersgroup' => 1,
      'state' => 1,
    ];

    $this->db(1)->selectCollection('usersaccount')->insertOne($document);
  }

  /**
   * Create user if not exist
   */
  private function CreateRedisDbFirstUserIfNotExist()
  {
    
    $document =[
      'login' => 'admin',
      'password' => $this->Guard->CryptPassword('admin'),
      'namesurname' => NULL,
      'contact' => NULL,
      'email' => NULL,
      'usersgroup' => 1,
      'state' => 1,
    ];

    $this->key('usersaccount')->id('idusers')->index('admin')->param($document)->addToRedis();
  }  
}
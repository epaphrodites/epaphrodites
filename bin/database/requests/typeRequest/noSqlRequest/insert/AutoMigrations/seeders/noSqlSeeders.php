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
      'idusers'=> new ObjectId,
      'loginusers'=>'admin',
      'userspwd'=>$this->Guard->CryptPassword('admin'),
      'nomprenomsusers'=> NULL,
      'contactusers'=> NULL,
      'emailusers'=> NULL,
      'usersstat'=> 1,
      'typeusers'=> 1,
    ];

    $this->db(1)->selectCollection('useraccount')->insertOne($document);
  }

  /**
   * Create user if not exist
   */
  private function CreateRedisDbFirstUserIfNotExist()
  {
    
    $document =[
      'loginusers'=>'admin',
      'userspwd'=>$this->Guard->CryptPassword('admin'),
      'nomprenomsusers'=> NULL,
      'contactusers'=> NULL,
      'emailusers'=> NULL,
      'usersstat'=> 1,
      'typeusers'=> 1,
    ];

    $this->key('useraccount')->id('idusers')->index('admin')->param($document)->addToRedis();
  }  


}
<?php

namespace Epaphrodite\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations;

use Epaphrodite\database\query\Builders;
use Epaphrodite\epaphrodite\danho\GuardPassword;
use Epaphrodite\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\seeders\noSqlSeeders;
use Epaphrodite\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\migrations\mongodbMigrations;
use Epaphrodite\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\migrations\redisdbMigrations;

class InitNoSeederGenerated extends Builders
{

  use mongodbMigrations, redisdbMigrations, noSqlSeeders;

  protected $Guard;

  public function __construct()
  {
    $this->Guard = new GuardPassword;
  }

  /** 
   * generate to Mongo tables if not exist
   */
  public function CreateMongoCollections()
  {

    $this->CreateMongoUserIfNotExist();

    $this->CreateFirstUserIfNotExist();

    $this->CreateAuthSecureMongoIfNotExist();

    $this->CreateChatMessagesMongoIfNotExist();

    $this->createRecentlyActionsMongoIfNotExist();
  }


  /** 
   * generate to Mongo tables if not exist
   */
  public function CreateRedisMigration()
  {

    $this->CreateRedisDbUserIfNotExist();

    $this->CreateRedisDbFirstUserIfNotExist();

    $this->CreateAuthSecureRedisDbIfNotExist();

    $this->CreateChatMessagesRedisDbIfNotExist();

    $this->createRecentlyActionsRedisDbIfNotExist();
  }  
}

<?php

namespace Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations;

use Epaphrodite\database\query\Builders;
use Epaphrodite\epaphrodite\danho\GuardPassword;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\seeders\sqlSeeder;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations\mysqlMigrations;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations\sqLiteMigrations;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations\postgreSqlMigrations;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations\sqlServerMigrations;

class InitSeederGenerated extends Builders
{

  use mysqlMigrations, postgreSqlMigrations, sqLiteMigrations, sqlServerMigrations, sqlSeeder;
  protected $Guard;

  public function __construct()
  {
    $this->Guard = new GuardPassword;
  }

  /** 
   * generate to MySQL tables if not exist
   */
  public function createTableMysql()
  {

    $this->CreateUserIfNotExist();

    $this->CreateAuthSecureIfNotExist();

    $this->CreateChatMessagesIfNotExist();

    $this->createRecentlyActionsIfNotExist();

    $this->CreateFirstUserIfNotExist();
  }

  /** 
   * generate to PostgreSQL tables if not exist
   */
  public function createTablePostgreSQL()
  {

    $this->CreatePostgeSQLUserIfNotExist();

    $this->CreateAuthSecurePostgeSQLIfNotExist();

    $this->CreateChatMessagesPostgeSQLIfNotExist();

    $this->createRecentlyActionsPostgeSQLIfNotExist();

    $this->CreateFirstUserIfNotExist();
  }

  /** 
   * generate to SqLite tables if not exist
   */
  public function createTableSqLite()
  {

    $this->CreateSqLiteUserIfNotExist();

    $this->CreateAuthSecureSqLiteIfNotExist();

    $this->CreateChatMessagesSqLiteIfNotExist();

    $this->createRecentlyActionsSqLiteIfNotExist();

    $this->CreateFirstUserIfNotExist();
  } 
  
  /** 
   * generate to SqLite tables if not exist
   */
  public function createTableSqlServer()
  {

    $this->CreateSqlServerUserIfNotExist();

    $this->CreateSqlServerAuthSecureIfNotExist();

    $this->CreateSqlServerChatMessagesIfNotExist();

    $this->createSqlServerRecentlyActionsIfNotExist();

    $this->CreateFirstUserIfNotExist();
  }    
}

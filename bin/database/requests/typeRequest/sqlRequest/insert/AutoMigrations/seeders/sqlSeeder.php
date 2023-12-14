<?php

namespace Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\seeders;

trait sqlSeeder{
   
  /**
   * Create user if not exist
   * @return void
   */
  private function CreateFirstUserIfNotExist():void
  {

    $this->table('useraccount')
      ->insert('loginusers , userspwd')
      ->values( ' ? , ? ' )
      ->param(['admin', $this->Guard->CryptPassword('admin')])
      ->IQuery();
  } 
}
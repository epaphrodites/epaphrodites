<?php

namespace Epaphrodites\database\seeders;

use MongoDB\BSON\ObjectId;
use Epaphrodites\database\query\Builders;

class databaseSeeding extends Builders{

    /**
     * Seed the application's database (sql/nosql).
     */    
    // public function noSqlRun(): void
    // {
    //      $document =[
    //         'idusers'=> new ObjectId,
    //          'loginusers'=>'admin',
    //         'userspwd'=>static::initConfig()['guard']->CryptPassword('admin'),
    //          'usersname'=> NULL,
    //          'contactusers'=> NULL,
    //          'emailusers'=> NULL,
    //          'usersstat'=> 1,
    //         'usersgroup'=> 1,
    //        ];
      
    //       $this->db(1)->selectCollection('users_account')
    //                    ->insertOne($document);        
    // }

    /**
     * Seed the application's database.
     */    
    // public function sqlRun(): void
    // {
    //     $this->table('users_account')
    //         ->insert('idusers_account , name , surname')
    //         ->values( ' ? , ? , ?' )
    //         ->param([8, "name" , "dialla" ])
    //         ->sdb(1)
    //         ->IQuery(); 
    // }    
}
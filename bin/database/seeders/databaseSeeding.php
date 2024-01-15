<?php

namespace Epaphrodites\database\seeders;

use MongoDB\BSON\ObjectId;
use Epaphrodites\database\query\Builders;

class databaseSeeding extends Builders{

    /**
     * Seed the application's database (sql/nosql).
     */    
    public function noSqlRun(): void
    {
        // $document =[
        //     'idusers'=> new ObjectId,
        //     'loginusers'=>'admin',
        //     'userspwd'=>static::initConfig()['guard']->CryptPassword('admin'),
        //     'nomprenomsusers'=> NULL,
        //     'contactusers'=> NULL,
        //     'emailusers'=> NULL,
        //     'usersstat'=> 1,
        //     'typeusers'=> 1,
        //   ];
      
        //   $this->db(1)->selectCollection('useraccount')
        //                 ->insertOne($document);        
    }

    /**
     * Seed the application's database.
     */    
    public function sqlRun(): void
    {
        // $this->table('useraccount')
        //     ->insert('loginusers , userspwd , typeusers')
        //     ->values( ' ? , ? , ?' )
        //     ->param(['user', static::initConfig()['guard']->CryptPassword('user') , 1 ])
        //     ->IQuery(); 
    }    
}
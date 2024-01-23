<?php

namespace Epaphrodites\database\gearShift;

use Epaphrodites\database\query\buildQuery\buildGearShift;

class databaseGearShift extends buildGearShift{


    public function up(){

        // return $this->createTable('users_test', function ($table) {

        //     $table->addColumn('idusers', 'INTEGER', ['PRIMARY KEY']);
        //     $table->addColumn('name', 'VARCHAR(255)');
        // });
    }

    public function down(){
        
        // return $this->dropTable('users_test', function ($table) {
            
        // });
    }   
    
}
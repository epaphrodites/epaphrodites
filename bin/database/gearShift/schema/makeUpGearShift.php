<?php 

namespace Epaphrodites\database\gearShift\schema;


trait makeUpGearShift{

    /**
     * Create table users_account
     * create 25/01/2024
     */
    public function createUsersAccountTable()
    {
        return $this->createTable('users_account', function ($table) {
                $table->addColumn('idusers_account', 'INTEGER', ['PRIMARY KEY']);
                $table->addColumn('name', 'VARCHAR(100)');
                $table->addColumn('surname', 'VARCHAR(100)');
                $table->db(1);
        });
    }                                      
}
<?php 

namespace Epaphrodites\database\gearShift\schema;

trait makeUpGearShift{

    /**
     * Create table users_account
     * create 25/01/2024 23:07:14
     */
    public function createUsersAccountTable()
    {
        return $this->createTable('users_account', function ($table) {
                $table->addColumn('idusers_account', 'SERIAL', ['PRIMARY KEY']);
                $table->addColumn('username', 'VARCHAR' , ['NOT NULL']);
                $table->addColumn('password', 'VARCHAR' , ['NOT NULL']);
                $table->addColumn('usersemail', 'VARCHAR' , ['NOT NULL']);
                $table->addIndex('usersemail');
                $table->db(2);
        });
    }       
}
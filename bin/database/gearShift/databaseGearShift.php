<?php

namespace Epaphrodites\database\gearShift;

use Epaphrodites\database\query\buildQuery\buildGearShift;

class databaseGearShift extends buildGearShift{

    /**
     * @return string
     */
    public function addGearShift(){

       $request = $this->createTable('userstest', function ($table) {

            $table->addColumn('user_id', 'INT', ['PRIMARY KEY']);
            $table->addColumn('name', 'VARCHAR(255)');
            $table->addColumn('created_at', 'TIMESTAMP');
            $table->addIndex(['user_id']);
            $table->addIndex(['name', 'created_at'], 'custom_index_name');
        });

        return $request;
    }

}
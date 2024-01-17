<?php

namespace Epaphrodites\database\gearShift;

use Epaphrodites\database\query\buildQuery\buildGearShift;

class databaseGearShift extends buildGearShift{

    /**
     * @return string
     */
    public function addGearShift():string{

        $sql = $this->generateTable('users', function ($table) {
            $table->id();
            $table->string('name');
        });
        
        return $sql;        
    }

}
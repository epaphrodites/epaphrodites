<?php

namespace Epaphrodites\database\query\buildQuery;
use Epaphrodites\database\query\buildChaines\gearQueryChaines;

class buildGearShift{

    use gearQueryChaines;

    public function generateTable($tableName, $callback)
    {
       return $this->createTable($tableName, $callback);
    }    
}
<?php

namespace Epaphrodites\database\query\buildQuery;
use Epaphrodites\database\query\buildChaines\gearQueryChaines;

class buildGearShift{

    use gearQueryChaines;

    public function generateTable($tableName, callable $callback)
    {
        $this->columns = [];

        $callback($this);

        $columns = implode(', ', $this->getColumns());

        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` ($columns)";

        return $sql;
    }    
}
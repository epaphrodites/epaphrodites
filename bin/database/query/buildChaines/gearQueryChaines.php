<?php

namespace Epaphrodites\database\query\buildChaines;

trait GearQueryChaines
{

    private $tableName;
    private $columns = [];
    private $indexes = [];

    public function createTable($tableName, $callback)
    {
        $this->tableName = $tableName;
        $callback($this);
        return $this->executeMigration();
    }

    public function dropTable($table)
    {

        $sql = "DROP TABLE IF EXISTS {$table}";
        $this->executeQuery($sql);
        return $sql;
    }
    
    public function dropColumn($column)
    {
        $sql = "ALTER TABLE {$this->tableName} DROP COLUMN IF EXISTS {$column};";
        return $this->executeQuery($sql);
    }

    public function addColumn($columnName, $type, $options = [])
    {
        $this->columns[] = compact('columnName', 'type', 'options');
        return $this;
    }

    private function executeMigration()
    {
        $sql = $this->generateSQL();
        return $sql;
    }

    private function generateSQL()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";

        foreach ($this->columns as $column) {
            $columnName = $column['columnName'];
            $type = $column['type'];
            $options = isset($column['options']) ? implode(' ', $column['options']) : '';
            $sql .= "$columnName $type $options, ";
        }

        $sql = rtrim($sql, ', ');
        $sql .= ")";

        return $sql;
    }
    

    private function executeQuery($sql)
    {
        echo $sql . PHP_EOL;
    }
}


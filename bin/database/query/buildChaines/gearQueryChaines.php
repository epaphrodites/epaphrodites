<?php

namespace Epaphrodites\database\query\buildChaines;

trait gearQueryChaines
{
    private $tableName;
    private $columns = [];
    private $indexes = [];

    public function createTable($tableName, $callback) {

        $this->tableName = $tableName;
        $callback($this);
        $this->executeMigration();
    }

    public function addColumn($columnName, $type, $options = []) {
        $this->columns[] = compact('columnName', 'type', 'options');
        return $this;
    }

    public function addIndex($columns, $indexName = null) {
        $this->indexes[] = compact('columns', 'indexName');
        return $this;
    }

    private function executeMigration() {
        
        echo $this->generateSQL();
    }

    private function generateSQL() {
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";
        foreach ($this->columns as $column) {
            $columnName = $column['columnName'];
            $type = $column['type'];
            $options = implode(' ', $column['options']);
            $sql .= "$columnName $type $options, ";
        }

        foreach ($this->indexes as $index) {
            $columns = implode(', ', $index['columns']);
            $indexName = $index['indexName'] ? $index['indexName'] : uniqid("idx");
            $sql .= "INDEX $indexName ($columns), ";
        }

        $sql = rtrim($sql, ', ');
        $sql .= ")";

        return $sql;
    }
}

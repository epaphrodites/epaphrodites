<?php

namespace Epaphrodites\database\query\buildChaines;

trait gearQueryChaines
{
    private ?string $tableName = null;
    private array $columns = [];


    public function createTable(string $tableName, callable $callback): string
    {
        $this->tableName = $tableName;
        $callback($this);
        return $this->executeMigration();
    }

    public function dropTable(string $table, callable $callback = null): string
    {
        $sql = "DROP TABLE IF EXISTS {$table}";

        if ($callback !== null) {
            
            $additionalSql = $callback($this);
    
            if (!empty($additionalSql)) {
                
                $sql .= strpos($additionalSql, 'DROP COLUMN') !== false ? ' ' . $additionalSql : ' ' . trim($additionalSql, ';') . ';';
            }
        }

        return $sql;
    }

    public function dropColumn(string $column): string
    {
        $sql = "ALTER TABLE {$this->tableName} DROP COLUMN IF EXISTS {$column};";

        return $sql;
    }

    public function addColumn(string $columnName, string $type, array $options = []): self
    {
        $this->columns[] = compact('columnName', 'type', 'options');
        return $this;
    }

    private function executeMigration(): string
    {
        $sql = $this->generateSQL();
        return $sql;
    }

    private function generateSQL(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";

        foreach ($this->columns as $column) {
            $columnName = $column['columnName'];
            $type = $column['type'];
            $options = isset($column['options']) ? implode(' ', $column['options']) : '';
            $sql .= "{$columnName} {$type} {$options}, ";
        }

        $sql = rtrim($sql, ', ');
        $sql .= ")";

        return $sql;
    }
}



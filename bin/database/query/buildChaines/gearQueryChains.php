<?php

namespace Epaphrodites\database\query\buildChaines;

use Epaphrodites\database\config\ini\GetConfig;

trait gearQueryChains
{
    private ?string $tableName = null;
    private array $columns = [];
    private ?array $dropColumn = null;
    private int $db ;

    /**
     * @param int $db
     */
    public function db(int $db = 1):self
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Create a new table with the specified name and callback.
     * @param string   $tableName The name of the table to be created.
     * @param callable $callback  The callback function to define table columns and properties.
     * @return array The generated SQL for creating the table.
     */
    public function createTable(string $tableName, callable $callback)
    {
        $this->reset();
        $this->tableName = $tableName;
        $callback($this);
        return $this->generateSQL();
    }

    /**
     * Create a new table with the specified name and callback.
     * @param string   $tableName The name of the table to be created.
     * @param callable $callback  The callback function to define table columns and properties.
     * @return array The generated SQL for creating the table.
     */
    public function createColumn(string $tableName, callable $callback)
    {
        $this->reset();
        $this->tableName = $tableName;
        $callback($this);
        return $this->generateColumn();
    }    

    /**
     * Drop a table with the specified name and optional callback for additional configurations.
     * @param string $tableName The name of the table to be dropped.
     * @param callable $callback  Optional callback function for additional configurations.
     * @return array The generated SQL for dropping the table or columns.
     */
    public function dropTable(string $tableName, callable $callback = null)
    {
        $this->reset();
        $this->tableName = $tableName;
        if ($callback !== null) {
            $callback($this);
        }
        return $this->dropTableColumn();
    }

    /**
     * Specify a column to be dropped from the table.
     * @param string $column The name of the column to be dropped.
     * @return $this
     */
    public function dropColumn(string $column , array $option = []): self
    {
        $this->dropColumn[] = compact('column' , 'option');
        return $this;
    }

    /**
     * Add a new column to the table.
     *
     * @param string $columnName The name of the new column.
     * @param string $type The data type of the new column.
     * @param array $options Additional options for the new column.
     *
     * @return $this
     */
    public function addColumn(string $columnName, string $type = '', array $options = []): self
    {
        $this->columns[] = compact('columnName', 'type', 'options');
        return $this;
    }

    /**
     * Generate the SQL statement for creating the table.
     * @return string The generated SQL for creating the table.
     */
    private function generateSQL(): array
    {

        $db = empty($this->db) ? 1 : $this->db;

        $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (";

        foreach ($this->columns as $column) {
            $columnName = $column['columnName'];
            $type = $column['type'];
            $options = isset($column['options']) ? implode(' ', $column['options']) : '';
            $sql .= "{$columnName} {$type} {$options}, ";
        }

        $sql = rtrim($sql, ', ');
        $sql .= ")";

        return [ 'request' => $sql , 'db' => $db] ;
    }

    /**
     * @return array
     */
    public function generateColumn():array
    {
        
        $db = empty($this->db) ? 1 : $this->db;

        $sql = "ALTER TABLE {$this->tableName} ";
    
        $columnsSql = [];
    
        foreach ($this->columns as $column) {
            $columnName = $column['columnName'];
            $type = $column['type'];
            $options = isset($column['options']) ? implode(' ', $column['options']) : '';
            $columnsSql[] = "{$columnName} {$type} {$options}";
        }
    
        $sql .= "ADD COLUMN " . implode(', ADD COLUMN ', $columnsSql);
    
        return ['request' => $sql, 'db' => $db];
    }

    /**
     * Generate the SQL statement for dropping the table or columns.
     * @return string The generated SQL for dropping the table or columns.
     */
    private function dropTableColumn(): array
    {

        $db = empty($this->db) ? 1 : $this->db;

        $comma = $this->driver($db) !== 'sqlite' ? ',' : '';
    
        if (empty($this->dropColumn)) {
            
            $sql = "DROP TABLE IF EXISTS {$this->tableName}";
            return [ 'request' => $sql , 'db' => $db] ;
        }
    
        $sql = "ALTER TABLE {$this->tableName}";
    
        foreach ($this->dropColumn as $column) {

            $options = !empty($column['option']) ? implode(' ', $column['option']) : 'COLUMN';

            $sql .= " DROP {$options} {$column['column']}{$comma}";
        }
    
        $sql = rtrim($sql, $comma);

        return [ 'request' => $sql , 'db' => $db] ;
    }
    
    /**
     * Reset the properties of the trait.
     * @return void
     */
    private function reset(): void
    {
        $this->db = 1;
        $this->tableName = null;
        $this->columns = [];
        $this->dropColumn = null;
    }

    /**
     * Check database driver
     * @return string
     */
    private function driver($key):string
    {
        $db = max(1, (int) $key);
        return GetConfig::DB_DRIVER($db);
    }    
}
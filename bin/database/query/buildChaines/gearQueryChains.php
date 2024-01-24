<?php

namespace Epaphrodites\database\query\buildChaines;

use Epaphrodites\database\config\ini\GetConfig;
use Epaphrodites\database\gearShift\databaseGearShift;
trait gearQueryChains
{
    private ?string $tableName = null;
    private array $columns = [];
    private ?array $dropColumn = null;

    /**
     * Create a new table with the specified name and callback.
     * @param string   $tableName The name of the table to be created.
     * @param callable $callback  The callback function to define table columns and properties.
     * @return string The generated SQL for creating the table.
     */
    public function createTable(string $tableName, callable $callback): string
    {
        $this->reset();
        $this->tableName = $tableName;
        $callback($this);
        return $this->generateSQL();
    }

    /**
     * Drop a table with the specified name and optional callback for additional configurations.
     * @param string $tableName The name of the table to be dropped.
     * @param callable $callback  Optional callback function for additional configurations.
     * @return string The generated SQL for dropping the table or columns.
     */
    public function dropTable(string $tableName, callable $callback = null): string
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
    public function dropColumn(string $column): self
    {
        $this->dropColumn[] = compact('column');
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
    public function addColumn(string $columnName, string $type, array $options = []): self
    {
        $this->columns[] = compact('columnName', 'type', 'options');
        return $this;
    }

    /**
     * Generate the SQL statement for creating the table.
     * @return string The generated SQL for creating the table.
     */
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

    /**
     * Generate the SQL statement for dropping the table or columns.
     * @return string The generated SQL for dropping the table or columns.
     */
    private function dropTableColumn(): string
    {
        $comma = $this->driver() !== 'sqlite' ? ',' : '';
    
        if (empty($this->dropColumn)) {
            return "DROP TABLE IF EXISTS {$this->tableName}";
        }
    
        $sql = "ALTER TABLE {$this->tableName}";
    
        foreach ($this->dropColumn as $column) {
            $sql .= " DROP COLUMN {$column['column']}{$comma}";
        }
    
        return rtrim($sql, $comma);
    }
    

    /**
     * Reset the properties of the trait.
     * @return void
     */
    private function reset(): void
    {
        $this->tableName = null;
        $this->columns = [];
        $this->dropColumn = null;
    }

    /**
     * Check database driver
     * @return string
     */
    private function driver():string
    {
        $db = max(1, (int) $this->shift()->db());
        return GetConfig::DB_DRIVER($db);
    }    

   /**
     * Get an instance of the database gear shift.
     * @return databaseGearShift
    */    
    private function shift():databaseGearShift
    {
        return new databaseGearShift;
    }    
}




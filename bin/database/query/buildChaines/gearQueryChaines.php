<?php

namespace Epaphrodites\database\query\buildChaines;

trait gearQueryChaines
{
    protected $columns = [];
    protected int $db;

    public function id(string $id = "id")
    {
        $this->addColumn($id , 'INT', ['unsigned' => true, 'autoIncrement' => true, 'primary' => true]);

        return $this;
    }

    public function key(string $key = "id")
    {
        $this->addColumn($key , 'INTEGER PRIMARY KEY', ['unsigned' => true, 'autoIncrement' => true, 'primary' => true]);

        return $this;
    }    

    public function string($columnName, $length = 255)
    {
        $this->addColumn($columnName, 'string', ['length' => $length]);

        return $this;
    }

    public function text($columnName, $length = 255)
    {
        $this->addColumn($columnName, 'TEXT', ['length' => $length]);

        return $this;
    }    

    public function db(int $db = 1){

        $this->db = $db;
        $this;
    }

    public function timestamp($columnName)
    {
        $this->addColumn($columnName, 'TIMESTAMP');

        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    protected function addColumn($name, $type, $options = [])
    {

        $this->columns[] = "`$name` $type";
    }
}

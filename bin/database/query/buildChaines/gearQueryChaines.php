<?php

namespace Epaphrodites\database\query\buildChaines;

trait gearQueryChaines
{
    protected $columns = [];

    public function id(string $id = "id")
    {
        $this->addColumn($id , 'INT', ['unsigned' => true, 'autoIncrement' => true, 'primary' => true]);

        return $this;
    }

    public function string($columnName, $length = 255)
    {
        $this->addColumn($columnName, 'VARCHAR', ['length' => $length]);

        return $this;
    }

    public function timestamp($columnName)
    {
        $this->addColumn($columnName, 'TIMESTAMP');

        return $this;
    }

    // Autres méthodes pour d'autres types de colonnes...

    public function getColumns()
    {
        return $this->columns;
    }

    protected function addColumn($name, $type, $options = [])
    {

        $this->columns[] = "`$name` $type";
    }
}

<?php

namespace Epaphrodites\database\query\buildChaines;

trait buildQueryChaines
{

  /**
     * select query chaine
     *
     * @param array|null $propriety
     * @return array
     */
    public function SQuery($propriety=NULL): array
    {

        if ($propriety === NULL) {
            $propriety = '*';
        }

        /* 
        * Select initial query chaine
        */
        $query = "SELECT $propriety FROM {$this->table}";

        /* 
            Add join if exist
        */
        if ($this->join) {

            $query .= " {$this->join}";
        }

        /**
        * Add where if exist
        */
        if ($this->where) {
            $query .= " WHERE {$this->where}";
        }

        /** 
        * Add LIKE if exist
        */
        if ($this->like) {
            $query .= " WHERE {$this->like} LIKE ?";
        }

        /** 
        * Add match if exist
        */
        if ($this->match) {
            $query .= " WHERE MATCH ({$this->match}) AGAINST (?)";
        }

        /* 
        * Add BETWEEN if exist
        */
        if ($this->between) {
            $query .= " WHERE {$this->between} BETWEEN ? AND ? ";
        }

        /* 
            Add AND if exist
        */
        if ($this->and) {
            $query .= "{$this->and}";
        }

        /* 
            Add IS NOT NULL OR IS NULL if exist
        */
        if ($this->is) {
            $query .= " {$this->is}";
        }

        /* 
            Add OR if exist
        */
        if ($this->or) {
            $query .= "{$this->or}";
        }

        /* 
            Add ORDER BY if exist
        */
        if ($this->order) {
            $query .= " {$this->order}";
        }

        /* 
            Add GROUP BY if exist
        */
        if ($this->group) {
            $query .= " {$this->group}";
        }

        /* 
            Add HAVING if exist
        */
        if ($this->having) {
            $query .= " {$this->having}";
        }

        /* 
            Add LIMIT if exist
        */
        if ($this->limit) {
            $query .= " {$this->limit}";
        }

        return $this->selectBuildRequest($query);
    }


    /**
     * insert query chaine
     *
     * @return string
     */
    public function IQuery():string
    {

        /* 
            Insert initial query chaine
        */
        $Iquery = "INSERT INTO {$this->table} ";

        /* 
            Add DATAS if exist
        */
        if ($this->insert) {
            $Iquery .= "( {$this->insert} )";
        }

        /* 
            Add VALUES if exist
        */
        if ($this->values) {
            $Iquery .= " VALUES ( {$this->values} )";
        }

        return $this->executeBuildRequest($Iquery);
    }


    /**
     * Update query chaine
     *  @return mixed
     */
    public function UQuery():string
    {

        /* 
            Update inital query chaine
        */
        $query = "UPDATE {$this->table} ";

        /* 
            Add join if exist
        */
        if ($this->join) {

            $query .= " {$this->join}";
        }

        /* 
            Add SET if exist
        */
        if ($this->set) {
            $query .= " SET {$this->set}";
        }

        /* 
            Add SET if exist
        */
        if ($this->set_i) {
            $query .= " SET {$this->set_i}";
        }

        /* 
            Add REPLACE if exist
        */
        if ($this->replace) {
            $query .= " SET {$this->replace}";
        }

        /* 
            Add WHERE if exist
        */
        if ($this->where) {
            $query .= " WHERE {$this->where} ";
        }

        /* 
            Add IS NOT NULL OR IS NULL if exist
        */
        if ($this->is) {
            $query .= " {$this->is}";
        }

        /* 
            Add match if exist
        */
        if ($this->match) {
            $query .= " WHERE MATCH ({$this->match}) AGAINST (?)";
        }

        /* 
            Add BETWEEN if exist
        */
        if ($this->between) {
            $query .= " WHERE {$this->between} BETWEEN ? AND ? ";
        }

        /* 
            Add LIKE if exist
        */
        if ($this->like) {
            $query .= " WHERE {$this->like} LIKE ? ";
        }

        /* 
            Add AND if exist
        */
        if ($this->and) {
            $query .= " {$this->and}";
        }

        /* 
            Add OR if exist
        */
        if ($this->or) {
            $query .= "{$this->or}";
        }

        /* 
            Add ORDER BY if exist
        */
        if ($this->order) {
            $query .= " {$this->order}";
        }

        /* 
            Add HAVING if exist
        */
        if ($this->having) {
            $query .= " {$this->having}";
        }

        /* 
            Add LIMIT if exist
        */
        if ($this->limit_i) {
            $query .= " {$this->limit_i}";
        }

        return $this->executeBuildRequest($query);
    }

    /**
     * Delete query chaine
     *
     * @return mixed
     */
    public function DQuery():string
    {

        /* 
            Update inital query chaine
        */
        $query = "DELETE FROM {$this->table} ";

        /* 
            Add WHERE if exist
        */
        if ($this->where) {
            $query .= " WHERE {$this->where} ";
        }

        /* 
            Add LIKE if exist
        */
        if ($this->like) {
            $query .= " WHERE {$this->like} LIKE ? ";
        }

        /* 
            Add IS NOT NULL OR IS NULL if exist
        */
        if ($this->is) {
            $query .= " {$this->is}";
        }

        /* 
            Add match if exist
        */
        if ($this->match) {
            $query .= " WHERE MATCH ({$this->match}) AGAINST (?)";
        }

        /* 
            Add BETWEEN if exist
        */
        if ($this->between) {
            $query .= " WHERE {$this->between} BETWEEN ? AND ? ";
        }

        /* 
            Add AND if exist
        */
        if ($this->and) {
            $query .= " {$this->and}";
        }

        /* 
            Add OR if exist
        */
        if ($this->or) {
            $query .= "{$this->or}";
        }

        /* 
            Add HAVING if exist
        */
        if ($this->having) {
            $query .= " {$this->having}";
        }

        /* 
            Add LIMIT if exist
        */
        if ($this->limit_i) {
            $query .= " {$this->limit_i}";
        }

        return $this->executeBuildRequest($query);
    }   
    
    
    public function addToRedis(int $db = 1){

        $getConnexion = $this->rdb($db);

        $jsonData = json_encode($this->param);

        $key = "{$getConnexion['db']}:{$this->key}";

        return $getConnexion['connexion']->set($key, $jsonData);
    }


    public function isExist(int $db = 1){

        $getConnexion = $this->rdb($db);

        $key = "{$getConnexion['db']}:{$this->key}";

        return $getConnexion['connexion']->exists($key) ? true : false;
    }

    public function redisGet(int $db = 1){

    }

}
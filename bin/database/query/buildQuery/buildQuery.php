<?php

namespace Epaphrodites\database\query\buildQuery;

use Epaphrodites\database\config\process\getDatabase;

trait buildQuery
{
    /**
     * Get the built SELECT query
     *
     * @return string
     */
    public function getQuery(): string
    {
        $query = "$this->chaine"; // Get initial query chain

        // Execute the SELECT query
        return $this->selectBuildRequest($query);
    }   
    
    /**
     * Set and execute the query
     *
     * @return string
     */
    public function setQuery(): string
    {
        $query = "$this->chaine"; // Get initial query chain
        // Execute the query
        return $this->executeBuildRequest($query);
    }     

    /**
     * Execute SELECT query
     *
     * @param string $query The query to execute
     * @return mixed The query execution result
     */
    public function selectBuildRequest($query)
    {
        $param = $this->param ?? null;
        $db = $this->db ?? 1;
        $setParam = !is_null($this->param);
        $close = !is_null($this->close);
    
        // Execute the SELECT query and return the result
        return static::initConfig()['process']->select($query, $param, $setParam, $close, $db);
    }

    /**
     * Execute INSERT query
     *
     * @param string $query The query to execute
     * @return mixed The query execution result
     */
    public function executeBuildRequest($query)
    {
        $param = $this->param ?? null;
        $db = $this->db ?? 1;
        $setParam = !is_null($this->param);
        $close = !is_null($this->close);

        // Execute the INSERT query and return the result
        return static::initConfig()['process']->runRequest($query, $param, $setParam, $close, $db);
    }    

    /**
     * Get the database connection
     *
     * @param mixed $db The database reference
     * @return mixed The database connection
     */
    public function db($db)
    {

        // Return the database connexion
        return (new getDatabase)->GetConnexion($db);
    }

    /**
     * Get the database connection
     *
     * @param mixed $db The database reference
     * @return mixed The database connection
     */
    public function rdb($db)
    {
        
        return (new getDatabase)->GetConnexion($db);
    }
}

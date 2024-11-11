<?php

namespace Epaphrodites\database\query\buildQuery;

use Epaphrodites\database\config\process\getDatabase;

trait buildQuery
{
    /**
     * Get the built SELECT query
     *
     * @return mixed
     */
    public function getQuery(
        int $db = 1
    ): mixed
    {
        $query = "$this->chaine"; // Get initial query chain

        // Execute the SELECT query
        return $this->selectBuildRequest($query , $db);
    }   
    
    /**
     * Set and execute the query
     *
     * @return string
     */
    public function setQuery(
        int $db = 1
    ): string
    {
        $query = "$this->chaine";
        
        return $this->executeBuildRequest($query , $db);
    }  
    
    /**
     * Set and execute the query
     *
     * @return string
     */
    public function setMultiQuery(
        int $db = 1
    ): bool
    {
        $result = false;
        
        foreach($this->multiChaine as $query){
    
            if(!empty($query)){
    
                $result = $this->executeBuildRequest($query , $db);
            }
        }
        
        return $result;
    }   

    /**
     * Execute SELECT query
     *
     * @param string $query The query to execute
     * @return mixed The query execution result
     */
    public function selectBuildRequest(
        string $query , 
        int $db = 1
    ):mixed
    {
        $param = $this->param ?? null;
        $setParam = !is_null($this->param);
        $close = isset($this->close) && $this->close === true ? true : false;
        $db = isset($this->db)&&$db===1 ? $this->db : $db;
        $this->db = NULL;
        
        // Execute the SELECT query and return the result
        return static::initConfig()['process']->select($query, $param, $setParam, $close, $db);
    }

    /**
     * Execute INSERT/UPDATE/DELETE query
     *
     * @param string $query The query to execute
     * @return mixed The query execution result
     */
    public function executeBuildRequest(
        string $query, 
        int $db = 1
    ): mixed
    {
        $param = $this->param ?? null;
        $setParam = !is_null($this->param);
        $close = isset($this->close) && $this->close === true ? true : false;
        $db = isset($this->db)&&$db===1 ? $this->db : $db;
        $this->db = NULL;

        // Execute the INSERT query and return the result
        return static::initConfig()['process']->runRequest($query, $param, $setParam, $close, $db);
    }    

    /**
     * Get the database connection
     *
     * @param int|1 $db The database reference
     * @return mixed The database connection
     */
    public function db(int $db = 1)
    {
        // Return the database connexion
        return (new getDatabase)->GetConnexion($db);
    }

    /**
     * Get the database connection
     *
     * @param int|1 $db The database reference
     * @return mixed The database connection
     */
    public function rdb(int $db = 1)
    {
        // Return the database connexion
        return (new getDatabase)->GetConnexion($db);
    }
}
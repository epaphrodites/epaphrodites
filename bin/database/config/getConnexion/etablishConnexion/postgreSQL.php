<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait postgreSQL{

    /**
     * Connexion PostgreSQL
     * @param integer $db
     * @return object
    */
    private function setPostgreSQLConnexion(
        int $db
    ):object
    {
        // Try to connect to database to etablish connexion
        try {

            return new PDO(
                static::POSTGRES_SQL_DNS($db) . "dbname=" . static::DB_DATABASE($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::dbOptions()
            );

            // If impossible send error message        
        } catch (PDOException $e) {

            throw new PDOException(static::getError($e->getMessage()));
        }
    }

    /**
     * @param string $dbName
     * @param int $db
     * @param bool $actionType  
     * @return bool    
    */ 
    private function setPostgreSQLConnexionWithoutDatabase(
        string $dbName, 
        int $db, 
        bool $actionType
    ):bool
    {

       $requestAction = $actionType ? "CREATE" : "DROP";

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion = new PDO(
                static::POSTGRES_SQL_DNS($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::dbOptions()
            );

            $etablishConnexion->exec( "{$requestAction} DATABASE {$dbName}" );

            return true;
            
            // If impossible send error message        
        } catch (PDOException $e) {

            return false;
        }
    }    
    
    /**
     * @param integer $db
     * @return object
    */   
    public function PostgreSQL(int $db = 1){

        return $this->setPostgreSQLConnexion($db);
    }  
    
    /**
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
    */   
    public function etablishPostgreSQL(
        string $dbName, 
        int $db, 
        bool $actionType
    ):bool
    {

        return $this->setPostgreSQLConnexionWithoutDatabase($dbName , $db , $actionType);
    }    
}
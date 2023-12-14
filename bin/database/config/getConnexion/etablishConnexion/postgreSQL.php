<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait postgreSQL{

    /**
     * Connexion PostgreSQL
    */
    private function setPostgreSQLConnexion(int $db)
    {
        // Try to connect to database to etablish connexion
        try {

            return new PDO(
                "pgsql:" . static::DB_HOST($db) . ';' . static::DB_PORT($db) . "dbname=" . static::DB_DATABASE($db),
                    static::DB_USER($db),
                    static::DB_PASSWORD($db),
                    static::dbOptions()
            );

            // If impossible send error message        
        } catch (PDOException $e) {

            $this->getError($e->getMessage());
        }
    }

    private function setPostgreSQLConnexionWithoutDatabase(string $dbName , int $db)
    {

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion = new PDO(
                "pgsql:" . static::DB_HOST($db) . ';' . static::DB_PORT($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::dbOptions()
            );

            $etablishConnexion->exec( "CREATE DATABASE {$dbName}" );

            return true;
            
            // If impossible send error message        
        } catch (PDOException $e) {

            return false;
        }
    }    
    
    public function PostgreSQL(int $db){

        return $this->setPostgreSQLConnexion($db);
    }  
    
    public function etablishPostgreSQL(string $dbName , int $db ){

        return $this->setPostgreSQLConnexionWithoutDatabase($dbName , $db);
    }    
}
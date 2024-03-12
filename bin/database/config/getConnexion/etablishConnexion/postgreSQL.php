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

    private function setPostgreSQLConnexionWithoutDatabase(string $dbName , int $db , bool $actiomType)
    {

       $requestAction = $actiomType ? "CREATE" : "DROP";

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
    
    public function PostgreSQL(int $db){

        return $this->setPostgreSQLConnexion($db);
    }  
    
    public function etablishPostgreSQL(string $dbName , int $db , $actiomType ){

        return $this->setPostgreSQLConnexionWithoutDatabase($dbName , $db , $actiomType);
    }    
}
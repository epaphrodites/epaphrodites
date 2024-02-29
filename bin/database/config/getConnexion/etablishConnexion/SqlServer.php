<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait SqlServer{
    
    /**
     * Connexion Sql Serveur
    */
    private function setSqlServerConnexion(int $db)
    {

        // Try to connect to database to etablish connexion
        try {
            return new PDO(
                "sqlsrv:Server=" . static::DB_HOST($db) . "," . static::DB_PORT($db) . "Database=" . static::DB_DATABASE($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::sqlServerOption()
            );

            // If impossible send error message        
        } catch (PDOException $e) {

            $this->getError($e->getMessage());
        }
    }

    private function setSqlServerConnexionWithoutDatabase(string $dbName , int $db)
    {

        // Try to connect to database to etablish connexion
        try {

           $etablishConnexion = new PDO(
                "sqlsrv:Server=" . static::DB_HOST($db) . ';' . static::DB_PORT($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::sqlServerOption()
            );

           $etablishConnexion->exec( "CREATE DATABASE {$dbName}" );

            return true;
            
            // If impossible send error message        
        } catch (PDOException $e) {

            return false;
        }
    }  
    
    public function SqlServer(int $db){

        return $this->setSqlServerConnexion($db);
    }  
    
    public function etablishSqlServer(string $dbName , int $db ){

        return $this->setSqlServerConnexionWithoutDatabase($dbName , $db);
    }      

}
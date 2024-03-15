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
                static::SQL_SERVER_DNS($db) . "Database=" . static::DB_DATABASE($db) , 
                static::DB_USER($db), 
                static::DB_PASSWORD($db) , 
                static::sqlServerOption()
            );

            // If impossible send error message        
        } catch (PDOException $e) {

            throw new PDOException(static::getError($e->getMessage()));
        }
    }

    private function setSqlServerConnexionWithoutDatabase(string $dbName , int $db, bool $actionType)
    {

        $requestAction = $actionType ? "CREATE" : "DROP";

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion = new PDO(
                static::SQL_SERVER_DNS($db) , 
                static::DB_USER($db), 
                static::DB_PASSWORD($db) , 
                static::sqlServerOption()
            );

            $etablishConnexion->exec( "{$requestAction} DATABASE {$dbName}" );

            return true;
            
            // If impossible send error message        
        } catch (PDOException $e) {

            return false;
        }
    }  
    
    public function SqlServer(int $db = 1){

        return $this->setSqlServerConnexion($db);
    }  
    
    public function etablishSqlServer(string $dbName , int $db, bool $requestAction ){

        return $this->setSqlServerConnexionWithoutDatabase($dbName , $db, $requestAction);
    }      
}
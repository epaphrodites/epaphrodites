<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait oracledb{

    /**
     * Connexion Oracledb
     * @param integer $db
     * @return object
    */
    private function setOracledbConnexion(
        int $db
    ):object
    {
       
        // Try to connect to database to etablish connexion
        try {

           return new PDO(
                static::ORACLE_DNS($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::oracleOptions()
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
    private function setOracledbConnexionWithoutDatabase(
        string $dbName, 
        int $db, 
        bool $actionType
    )
    {

       $requestAction = $actionType ? "CREATE" : "DROP";

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion = new PDO(
                static::ORACLE_DNS($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::oracleOptions()
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
    public function oracledb(int $db = 1){

        return $this->setOracledbConnexion($db);
    }  
    
    /**
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
    */   
    public function etablishOracledb(
        string $dbName, 
        int $db, 
        bool $actionType
    ):bool
    {

        return $this->setOracledbConnexionWithoutDatabase($dbName , $db , $actionType);
    }    
}
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
     * @param integer $db
     * @return object
    */   
    public function oracledb(
        int $db = 1
    ){

        return $this->setOracledbConnexion($db);
    }   
}
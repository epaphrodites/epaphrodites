<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait mysql{

    /**
     * Connexion Mysql
     * @param integer $db
     * @return object
    */
    private function setMysqlConnexion(
        int $db
    ):object
    {

        // Try to connect to database to etablish connexion
        try {

            return new PDO(
                static::MYSQL_DNS($db) . 'dbname=' . static::DB_DATABASE($db),
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
     * Connexion to Mysql
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
    */
    private function setMysqlConnexionWithoutDatabase(
        string $dbName , 
        int $db, 
        bool $actionType
    ):bool
    {

        $requestAction = $actionType ? "CREATE" : "DROP";

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion =  new PDO(
                static::MYSQL_DNS($db),
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
     * Mysql database connexion
     * @param integer $db
     * @return object
     */
    public function Mysql(
        int $db = 1
    ):object
    {

        return $this->setMysqlConnexion($db);
    }

    /**
     * Connexion to Mysql
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
    */
    public function etablishMysql(
        string $dbName, 
        int $db, 
        bool $actionType
    ):bool{

        return $this->setMysqlConnexionWithoutDatabase($dbName, $db, $actionType);
    }    
}
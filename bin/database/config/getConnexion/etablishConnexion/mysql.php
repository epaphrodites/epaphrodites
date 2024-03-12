<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait mysql{

    /**
     * Connexion Mysql
     * @param int $db
    */
    private function setMysqlConnexion(int $db)
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
     * Connexion Mysql
     * @param int $db
    */
    private function setMysqlConnexionWithoutDatabase(string $dbName , int $db, bool $actiomType)
    {

        $requestAction = $actiomType ? "CREATE" : "DROP";

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
     * @param int $db
     */
    public function Mysql(int $db){

        return $this->setMysqlConnexion($db);
    }

    /**
     * Mysql database connexion
     * @param int $db
     */
    public function etablishMysql(string $dbName , int $db, bool $type){

        return $this->setMysqlConnexionWithoutDatabase($dbName  , $db, $type);
    }    
}
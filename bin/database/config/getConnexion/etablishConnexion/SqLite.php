<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait SqLite
{

    /**
     * Connexion PostgreSQL
     * @param integer $db
     * @return object
     */
    private function getSqLite(
        int $db
    ):object
    {

        // Try to connect to database to etablish connexion
        try {
            return new PDO(
                static::SQLITE_DNS($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::sqLiteOptions()
            );

            // If impossible send error message    
        } catch (PDOException $e) {

            throw new PDOException(static::getError($e->getMessage()));
        }
    }

    /**
     * Connexion SqLite
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
     */
    private function setSqLiteConnexionWithoutDatabase(
        string $dbName, 
        int $db, 
        bool $actionType
    ):bool
    {

        if($actionType==true) {

            // Try to connect to database to etablish connexion
            try {

                $dbFilePath = static::DB_SQLITE($db, $dbName);

                // Check if the SQLite database file exists
                if (file_exists($dbFilePath)) {
                    return false;
                }

                // Attempt to connect to the SQLite database
                new \SQLite3("$dbFilePath.sqlite");

                return true;

                // If impossible send error message    
            } catch (PDOException $e) {

                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * @param int $db
     * @return object
     */
    public function sqLite(
        int $db = 1
    ):object
    {

        return $this->getSqLite($db);
    }

    /**
     * @param string $dbName
     * @param int $db
     * @param bool $actionType
     * @return bool
     */
    public function etablishsqLite(
        string $dbName, 
        int $db, 
        bool $requestAction
    ): bool
    {

        return $this->setSqLiteConnexionWithoutDatabase($dbName, $db, $requestAction);
    }
}
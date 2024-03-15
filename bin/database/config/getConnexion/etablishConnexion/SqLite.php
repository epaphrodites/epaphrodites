<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

use PDO;
use PDOException;

trait SqLite
{

    /**
     * Connexion PostgreSQL
     */
    private function getSqLite(int $db)
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
     */
    private function setSqLiteConnexionWithoutDatabase(string $dbName, int $db, bool $requestAction)
    {

        if($requestAction==true) {

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
     * @return \PDO
     */
    public function sqLite(int $db = 1)
    {

        return $this->getSqLite($db);
    }

    /**
     * @param string $dbName
     * @param int $db
     * @return bool
     */
    public function etablishsqLite(string $dbName, int $db, bool $requestAction): bool
    {

        return $this->setSqLiteConnexionWithoutDatabase($dbName, $db, $requestAction);
    }
}
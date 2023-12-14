<?php

namespace Database\Epaphrodite\config\getConnexion\etablishConnexion;

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
                'sqlite:' . static::DB_SQLITE($db),
                static::DB_USER($db),
                static::DB_PASSWORD($db),
                static::sqLiteOptions()
            );

            // If impossible send error message    
        } catch (PDOException $e) {

            static::getError($e->getMessage());
        }
    }

    /**
     * Connexion SqLite
     */
    private function setSqLiteConnexionWithoutDatabase(string $dbName, int $db)
    {
        // Try to connect to database to etablish connexion
        try {

            $dbFilePath = static::DB_SQLITE($db, $dbName);

            // Check if the SQLite database file exists
            if (file_exists($dbFilePath)) {
                return false;
            }

            // Attempt to connect to the SQLite database
            new \SQLite3($dbFilePath);

            return true;

            // If impossible send error message    
        } catch (PDOException $e) {

            static::getError($e->getMessage());
        }
    }

    /**
     * @param int $db
     * @return \PDO
     */
    public function sqLite(int $db)
    {

        return $this->getSqLite($db);
    }

    /**
     * @param string $dbName
     * @param int $db
     * @return bool
     */
    public function etablishsqLite(string $dbName, int $db): bool
    {

        return $this->setSqLiteConnexionWithoutDatabase($dbName, $db);
    }
}
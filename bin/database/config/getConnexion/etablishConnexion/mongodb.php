<?php

namespace Database\Epaphrodites\config\getConnexion\etablishConnexion;

trait mongodb
{
    private $connection;

    /**
     * Connexion MongoDB
     */
    private function setMongoDBConnexion(int $db)
    {

        $param = [
            "username" => static::DB_USER($db),
            "password" => static::DB_PASSWORD($db)
        ];

        $options = empty(static::DB_USER($db)) && empty(static::DB_PASSWORD($db)) ? [] : $param;

        // Try to connect to database to etablish connexion
        try {
            $this->connection = new \MongoDB\Client("mongodb://" . static::noDB_HOST($db) . ":" . static::noDB_PORT($db), $options);
            return $this->connection->selectDatabase(static::DB_DATABASE($db));

            // If impossible send error message      
        } catch (\Exception $e) {
            
            throw static::getError($e->getMessage());
        }
    }

    /**
     * Connexion MongoDB
     */
    private function setMongoDBConnexionWithoutDatabase(string $dbName, int $db)
    {

        $param = [
            "username" => static::DB_USER($db),
            "password" => static::DB_PASSWORD($db)
        ];

        $options = empty(static::DB_USER($db)) && empty(static::DB_PASSWORD($db)) ? [] : $param;

        // Try to connect to database to etablish connexion
        try {

            $etablishConnexion = new \MongoDB\Client("mongodb://" . static::noDB_HOST($db) . ":" . static::noDB_PORT($db), $options);

            $listDatabases = $etablishConnexion->listDatabases();

            foreach ($listDatabases as $databaseInfo) {

                if ($databaseInfo->getName() === $dbName) {
                    return false;
                }
            }

            $database = $etablishConnexion->$dbName;
            $database->createCollection('collection');

            return true;

            // If impossible send error message      
        } catch (\Exception $e) {

            return false;
        }
    }

    public function MongoDB(int $db)
    {

        return $this->setMongoDBConnexion($db);
    }

    public function etablishMongoDB(string $dbName, int $db)
    {

        return $this->setMongoDBConnexionWithoutDatabase($dbName, $db);
    }
}
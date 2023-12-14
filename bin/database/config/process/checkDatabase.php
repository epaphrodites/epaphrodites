<?php

declare(strict_types=1);

namespace Epaphrodite\database\config\process;

use Epaphrodite\database\config\getConnexion\getConnexion;
use Epaphrodite\epaphrodite\ErrorsExceptions\epaphroditeException;
use Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\InitSeederGenerated;
use Epaphrodite\database\requests\typeRequest\noSqlRequest\insert\AutoMigrations\InitNoSeederGenerated;

class checkDatabase extends getConnexion
{

    protected function dbConnect(?int $db = 1)
    {

        // Switch based on the database driver type
        switch (static::DB_DRIVER($db)) {
                // If the driver is MySQL, connect to MySQL using the Mysql method
            case 'mysql':
                return $this->Mysql($db);
                break;

                // If the driver is PostgreSQL, connect to PostgreSQL using the PostgreSQL method
            case 'pgsql':
                return $this->PostgreSQL($db);
                break;

                // If the driver is sqlite, connect to sqlite using the sqlite method
            case 'sqlite':
                return $this->sqLite($db);
                break;

                // If the driver is SqlServer, connect to SqlServer using the MongoDB method
            case 'sqlserver':
                return $this->SqlServer($db);
                break;

                // If the driver is MongoDB, connect to MongoDB using the MongoDB method
            case 'mongodb':
                return $this->MongoDB($db);
                break;

                // If the driver is MongoDB, connect to MongoDB using the MongoDB method
            case 'redis':
                return $this->RedisDB($db);
                break;

            default:
                throw new epaphroditeException("Unsupported database driver");
        }
    }

    public function etablishConnect(string $dbName = NULL, int $db)
    {

        // Switch based on the database driver type
        switch (static::DB_DRIVER($db)) {

                // If the driver is MySQL, connect to MySQL using the Mysql method
            case 'mysql':
                return $this->etablishMysql($dbName, $db);
                break;

                // If the driver is PostgreSQL, connect to PostgreSQL using the PostgreSQL method
            case 'pgsql':
                return $this->etablishPostgreSQL($dbName, $db);
                break;

                // If the driver is sqlite, connect to sqlite using the sqlite method
            case 'sqlite':
                return $this->etablishsqLite($dbName, $db);
                break;

                // If the driver is sqlserver, connect to sqlserver using the sqlserver method
            case 'sqlserver':
                return $this->etablishSqlServer($dbName, $db);
                break;

                // If the driver is MongoDB, connect to MongoDB using the MongoDB method
            case 'mongodb':
                return $this->etablishMongoDB($dbName, $db);
                break;

            default:
                throw new epaphroditeException("Unsupported database driver");
        }
    }

    public function SeederGenerated(?int $db = 1)
    {
        // Switch based on the database driver type
        switch (static::DB_DRIVER($db)) {

                // If the driver is MySQL, create the table using InitSeederGenerated
            case 'mysql':
                return (new InitSeederGenerated)->createTableMysql();
                break;

                // If the driver is PostgreSQL, create the table using InitSeederGenerated
            case 'pgsql':
                return (new InitSeederGenerated)->createTablePostgreSQL();
                break;

                // If the driver is sqlLite, create collections using InitNoSeederGenerated
            case 'sqlite':
                return (new InitSeederGenerated)->createTableSqLite();
                break;

                // If the driver is sqlServer, create collections using InitNoSeederGenerated
            case 'sqlserver':
                return (new InitSeederGenerated)->createTableSqlServer();
                break;

                // If the driver is MongoDB, create collections using InitNoSeederGenerated
            case 'mongodb':
                return (new InitNoSeederGenerated)->createMongoCollections();
                break;

            default:
                throw new epaphroditeException("Unsupported database driver");
        }
    }
}

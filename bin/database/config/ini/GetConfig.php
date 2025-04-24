<?php

namespace Epaphrodites\database\config\ini;

use Epaphrodites\controllers\render\errors;
use PDO;

class GetConfig extends errors
{

     /**
      * sql Server OPTIONS
      * @return array<bool|int>
      */
     protected static function sqlServerOption(): array
     {
        return [
            PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    /**
     * @var array
     */
    protected static function dbOptions(): array
    {

        return [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES  => false,
            PDO::ATTR_PERSISTENT => true
        ];
    }

    /**
     * @var array
     */    
    protected static function oracleOptions():array{
        return[
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
    }

    /**
     * @var array
     */
    protected static function sqLiteOptions(): array
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }   

    /**
     * Load and parse the configuration file.
     *
     * @return array<string, array<string, mixed>> Parsed INI configuration as a structured array.
     * @throws \RuntimeException If the configuration file is missing or invalid.
     */
    private static function ConfigIniContent(): array
    {
        $configPath = _DIR_CONFIG_INI_ . 'Config.ini';

        if (!is_file($configPath)) {
            throw new \RuntimeException(message: "Configuration file not found: {$configPath}");
        }

        $parsed = parse_ini_file(
            filename: $configPath,
            process_sections: true,
            scanner_mode: INI_SCANNER_TYPED
        );

        if (!is_array($parsed) || $parsed === [] || $parsed === false) {
            throw new \RuntimeException(message: "Invalid or empty configuration file: {$configPath}");
        }

        return $parsed;
    }

    /**
     * Get the database section from config
     */
    private static function getSection(int $db): array
    {
        return static::ConfigIniContent()["{$db}_CONFIGURATION"];
    }

    /**
     * Get a specific key from the database section.
     *
     * @param int $db The database identifier.
     * @param string $key The key to retrieve.
     * @return string The value associated with the key, or an empty string if not found.
     */
    private static function get(int $db, string $key): string
    {
        return static::getSection($db)[$key] ?? '';
    }    

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_PORT(
        int $db
    ):string{

        $Port = static::get($db, 'PORT');

        return empty($Port) ?: 'port=' . $Port . ';';
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function SQL_SERVER_DB_PORT(
        int $db
    ):string{

        $Port = static::get($db, 'PORT');

        return empty($Port) ? ";" : ",{$Port};";
    }  
    
    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function ORACLE_PORT(
        int $db
    ):string{

        $Port = static::get($db, 'PORT');

        return empty($Port) ? '' : "(PORT = $Port)";
    }  
    
    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function ORACLE_CONNEXION(
        int $db
    ):string{

        $dbName = static::get($db, 'DATABASE');

        return "(CONNECT_DATA = (SERVICE_NAME = $dbName) )";
    }     

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function noDB_PORT(
        int $db
    ): string{

        $Port = static::get($db, 'PORT');

        return empty($Port) ?: $Port;
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_MysqlPORT(
        int $db
    ): string{

        $Port = static::get($db, 'PORT');

        return empty($Port) ?: "port={$Port}";
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_PASSWORD(
        int $db
    ): string{
;
        return static::get($db, 'PASSWORD');
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    public static function DB_DRIVER(
        int $db
    ): string{

        return static::get($db, 'DRIVER');
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_USER(
        int $db
    ): string{

        return static::get($db, 'USER');
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_DATABASE(
        int $db
    ): string{

        return static::get($db, 'DATABASE');
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_SQLITE(
        int $db, 
        string|null $dbName = null
    ): string{

        $dbName = $dbName ?? static::DB_DATABASE($db);

        return _DIR_SQLITE_DATAS_ . $dbName;
    }

    /**
     * @var string
     * @param int $db
     * @return string
     */
    protected static function DB_SOCKET(
        int $db
    ): string{

        return static::get($db, 'SOCKET');
    }

    /**
     * @var string
     * @param int $db
     * @return mixed
     */
    protected static function DB_HOST(
        int $db
    ):mixed{

        return static::DB_SOCKET($db) == false ? 'host=' . static::get($db, 'HOST') : static::get($db, 'SOCKET_PATH');
    }

    /**
     * @var string
     * @param int $db
     * @return mixed
     */
    protected static function SQL_SERVER_DB_HOST(
        int $db
    ):mixed{

        return static::DB_SOCKET($db) == false ? 'server=' . static::get($db, 'HOST') : static::get($db, 'SOCKET_PATH');
    } 
    
    /**
     * @var string
     * @param int $db
     * @return mixed
     */
    protected static function ORACLE_HOST(
        int $db
    ):mixed{

       return static::DB_SOCKET($db) == false ? "(HOST = ".static::get($db, 'HOST').")": static::get($db, 'SOCKET_PATH');
    }      

    /**
     * @var string
     * @param int $db
     * @return mixed
     */
    protected static function noDB_HOST(
        int $db
    ):mixed{

        return static::DB_SOCKET($db) == false ? static::get($db, 'HOST') : static::get($db, 'SOCKET_PATH');
    }

    /**
     * Error message
     * @param string|null $type
     * @return void
     */
    protected function getError(
        string|null $type = null
    ):void{

        $this->error_500($type);
    }
}
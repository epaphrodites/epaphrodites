<?php

namespace Epaphrodites\database\config\ini;

use Epaphrodites\controllers\render\errors;
use PDO;

class GetConfig extends errors
{

    /**
     * @var array
     */protected static function sqlServerOption(): array
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
     * @var string
     * @return array|bool
     */
    private static function ConfigIniContent():array|bool
    {
        $ini = _DIR_CONFIG_INI_ . "Config.ini";
        $content = parse_ini_file($ini, true);

        return $content;
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_PORT($db):string
    {

        $Port = static::ConfigIniContent()["{$db}DB_PORT"];

        return empty($Port) ?: 'port=' . $Port . ';';
    }

    /**
     * @var string
     * @return string
     */
    protected static function SQL_SERVER_DB_PORT($db):string
    {

        $Port = static::ConfigIniContent()["{$db}DB_PORT"];

        return empty($Port) ? ";" : ",{$Port};";
    }  
    
    /**
     * @var string
     * @return string
     */
    protected static function ORACLE_PORT($db):string
    {

        $Port = static::ConfigIniContent()["{$db}DB_PORT"];

        return empty($Port) ? '' : "(PORT = $Port)";
    }  
    
    /**
     * @var string
     * @return string
     */
    protected static function ORACLE_CONNEXION(int $db):string
    {

        $dbName = static::ConfigIniContent()["{$db}DB_DATABASE"];

        return "(CONNECT_DATA = (SERVICE_NAME = $dbName) )";
    }     

    /**
     * @var string
     * @return string
     */
    protected static function noDB_PORT($db): string
    {

        $Port = static::ConfigIniContent()["{$db}DB_PORT"];

        return empty($Port) ?: $Port;
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_MysqlPORT($db): string
    {

        $Port = static::ConfigIniContent()["{$db}DB_PORT"];

        return empty($Port) ?: "port={$Port}";
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_PASSWORD($db): string
    {

        return static::ConfigIniContent()["{$db}DB_PASSWORD"];
    }

    /**
     * @var string
     * @return string
     */
    public static function DB_DRIVER($db): string
    {

        return static::ConfigIniContent()["{$db}DB_DIVER"];
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_USER($db): string
    {

        return static::ConfigIniContent()["{$db}DB_USER"];
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_DATABASE($db): string
    {

        return static::ConfigIniContent()["{$db}DB_DATABASE"];
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_SQLITE($db , $dbName = null): string
    {
        //If $dbName is not provided, get the default database name based on $db
        $dbName = $dbName ?? static::DB_DATABASE($db);

        return _DIR_SQLITE_DATAS_ . $dbName;
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_SOCKET($db): string
    {

        return static::ConfigIniContent()["{$db}DB_SOCKET"];
    }

    /**
     * @var string
     * @return string
     */
    protected static function DB_HOST($db)
    {

        return static::DB_SOCKET($db) == false ? 'host=' . static::ConfigIniContent()["{$db}DB_HOST"] : static::ConfigIniContent()["{$db}DB_SOCKET_PATH"];
    }

    /**
     * @var string
     * @return string
     */
    protected static function SQL_SERVER_DB_HOST($db)
    {

        return static::DB_SOCKET($db) == false ? 'server=' . static::ConfigIniContent()["{$db}DB_HOST"] : static::ConfigIniContent()["{$db}DB_SOCKET_PATH"];
    } 
    
    /**
     * @var string
     * @return string
     */
    protected static function ORACLE_HOST($db)
    {

       return static::DB_SOCKET($db) == false ? "(HOST = ".static::ConfigIniContent()["{$db}DB_HOST"].")":static::ConfigIniContent()["{$db}DB_SOCKET_PATH"];
    }      

    /**
     * @var string
     * @return string
     */
    protected static function noDB_HOST($db)
    {

        return static::DB_SOCKET($db) == false ? static::ConfigIniContent()["{$db}DB_HOST"] : static::ConfigIniContent()["{$db}DB_SOCKET_PATH"];
    }

    /**
     * Error message
     * @param string|null $type
     * @return void
     */
    protected function getError(?string $type = null):void
    {

        $this->error_500($type);
    }
}
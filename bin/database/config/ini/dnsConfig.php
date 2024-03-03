<?php

namespace Epaphrodites\database\config\ini;

class dnsConfig extends GetConfig{

    /**
     * @return string
     */
    protected static function SQL_SERVER_DNS($db):string{

        return "sqlsrv:".static::SQL_SERVER_DB_HOST($db) . static::SQL_SERVER_DB_PORT($db);
    }

    /**
     * @return string
     */    
    protected static function MYSQL_DNS($db):string{

        return "mysql:" . static::DB_HOST($db) . ';' . static::DB_PORT($db);
    }   
    
    /**
     * @return string
     */    
    protected static function POSTGRES_SQL_DNS($db):string{

        return "pgsql:" . static::DB_HOST($db) . ';' . static::DB_PORT($db);
    }  
    
    /**
     * @return string
     */    
    protected static function SQLITE_DNS($db):string{

        return 'sqlite:' . static::DB_SQLITE($db);
    }   
}
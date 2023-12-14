<?php

namespace Epaphrodites\database\config\Contracts;

interface DriversConnexion
{

    /**
     * SqlServer connexion
     * 
     * @param int|1 $db
     * @return mixed
    */    
    public function SqlServer(int $db);

    /**
     * Mysql connexion
     * 
     * @param int|1 $db
     * @return mixed
    */     
    public function Mysql(int $db);

    /**
     * PostgreSQL connexion
     * 
     * @param int|1 $db
     * @return mixed
    */     
    public function PostgreSQL(int $db);

    /**
     * SqlLite connexion
     * 
     * @param int|1 $db
     * @return mixed
    */     
    public function SqLite(int $db);

    /**
     * MongoDB connexion
     * 
     * @param int|1 $db
     * @return mixed
    */     
    public function MongoDB(int $db);
}
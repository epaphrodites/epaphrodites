<?php

namespace Epaphrodite\database\config\Contracts;

interface DatabaseRequest
{

    /**
     * SQL request to select  
     * 
     * @param string|null $SqlChaine
     * @param string|null $param
     * @param array|null $datas
     * @param bool|null $etat
     * @param int|1 $bd
     * @return array
     * 
    */
    public function select($SqlChaine, $datas = [], ?bool $param=false , ?bool $etat = false , ?int $bd=1 ):array|NULL;


    /**
     * SQL run request  
     * 
     * @param string|null $SqlChaine
     * @param string|null $param
     * @param array|null $datas
     * @param bool|null $etat
     * @param int|1 $bd
     * @return bool
     * 
    */    
    public function runRequest($SqlChaine, $datas=[], ?bool $param=false , ?bool $etat = false , ?int $db=1):bool|NULL;
}
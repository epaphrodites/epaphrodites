<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait mysqlMigrations
{

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createHistoryIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS history (
                idhistory INTEGER(11) NOT NULL AUTO_INCREMENT , 
                actions VARCHAR(20)NOT NULL , 
                date DATETIME , 
                label VARCHAR(300)NOT NULL , 
                PRIMARY KEY(idhistory) , 
                INDEX (actions) )")->setQuery();
    }

    /**
     * Create authsecure if not exist
     * @return void
     */
    private function CreateAuthSecureIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS secure 
              (idsecure INTEGER(11) NOT NULL AUTO_INCREMENT , 
              auth VARCHAR(300) NOT NULL , 
              token VARCHAR(200) NOT NULL , 
              createat DATETIME , 
              PRIMARY KEY(idsecure) , 
              INDEX(auth) )")->setQuery();
    }

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateUserIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS usersaccount (
                idusers INTEGER(11) NOT NULL AUTO_INCREMENT , 
                login VARCHAR(20)NOT NULL , 
                password VARCHAR(100) NOT NULL , 
                namesurname VARCHAR(150) DEFAULT NULL , 
                contact VARCHAR(10) DEFAULT NULL , 
                email VARCHAR(50) DEFAULT NULL , 
                usersgroup INTEGER(1) NOT NULL DEFAULT '1' , 
                state INTEGER(1) NOT NULL DEFAULT '1' , 
                PRIMARY KEY(idusers) , 
                INDEX (login) )")->setQuery();
    }
}

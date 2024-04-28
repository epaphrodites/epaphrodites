<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait mysqlMigrations
{

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createRecentlyActionsIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
                recentactions (idrecentactions int(11) NOT NULL AUTO_INCREMENT , 
                usersactions VARCHAR(20)NOT NULL , 
                dateactions DATETIME , 
                libactions VARCHAR(300)NOT NULL , 
                PRIMARY KEY(idrecentactions) , 
                INDEX (usersactions) )")->setQuery();
    }

    /**
     * Create authsecure if not exist
     * @return void
     */
    private function CreateAuthSecureIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS authsecure 
              (idtokensecure int(11) NOT NULL AUTO_INCREMENT , 
              crsfauth VARCHAR(300)NOT NULL , 
              authkey VARCHAR(200) NOT NULL , 
              createat DATETIME , 
              PRIMARY KEY(idtokensecure) , 
              INDEX (crsfauth) )")->setQuery();
    }

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateUserIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
                useraccount (idusers int(11) NOT NULL AUTO_INCREMENT , 
                loginusers VARCHAR(20)NOT NULL , 
                userspwd VARCHAR(100) NOT NULL , 
                usersname VARCHAR(150) DEFAULT NULL , 
                contactusers VARCHAR(10) DEFAULT NULL , 
                emailusers VARCHAR(50) DEFAULT NULL , 
                usersgroup int(1) NOT NULL DEFAULT '1' , 
                usersstat int(1) NOT NULL DEFAULT '1' , 
                PRIMARY KEY(idUsers) , 
                INDEX (loginusers) )")->setQuery();
    }
}

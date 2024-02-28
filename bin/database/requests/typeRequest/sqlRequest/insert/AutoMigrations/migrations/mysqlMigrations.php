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
                recentactions (idrecentactions int(11) NOT NULL auto_increment , 
                usersactions varchar(20)NOT NULL , 
                dateactions datetime , 
                libactions varchar(300)NOT NULL , 
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
              (idtokensecure int(11) NOT NULL auto_increment , 
              crsfauth varchar(300)NOT NULL , 
              authkey varchar(200) NOT NULL , 
              createat datetime , 
              PRIMARY KEY(idtokensecure) , 
              INDEX (crsfauth) )")->setQuery();
    }

    /**
     * Create messages if not exist
     * @return void
     */
    private function CreateChatMessagesIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
                chatsmessages (idchatsmessages int(11) NOT NULL auto_increment , 
                emetteur varchar(20)NOT NULL , 
                destinataire varchar(20) NOT NULL , 
                typemessages int(1) NOT NULL , 
                contentmessages varchar(500) NOT NULL , 
                datemessages datetime , 
                etatmessages int(1) NOT NULL , 
                PRIMARY KEY(idchatsMessages) , 
                INDEX (emetteur) , INDEX (destinataire) )")->setQuery();
    }

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateUserIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
                useraccount (idusers int(11) NOT NULL auto_increment , 
                loginusers varchar(20)NOT NULL , 
                userspwd varchar(100) NOT NULL , 
                usersname varchar(150) DEFAULT NULL , 
                contactusers varchar(10) DEFAULT NULL , 
                emailusers varchar(50) DEFAULT NULL , 
                usersgroup int(1) NOT NULL DEFAULT '1' , 
                usersstat int(1) NOT NULL DEFAULT '1' , 
                PRIMARY KEY(idUsers) , 
                INDEX (loginusers) )")->setQuery();
    }
}

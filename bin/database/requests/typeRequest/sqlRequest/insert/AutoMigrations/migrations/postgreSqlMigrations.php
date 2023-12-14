<?php

namespace Epaphrodite\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait postgreSqlMigrations
{


    /**
     * Create user if not exist
     * @return void
     */
    private function CreatePostgeSQLUserIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
            useraccount (idusers SERIAL PRIMARY KEY, 
            loginusers varchar(20) NOT NULL , 
            userspwd varchar(100) NOT NULL , 
            nomprenomsusers varchar(150) DEFAULT NULL , 
            contactusers varchar(10) DEFAULT NULL , 
            emailusers varchar(50) DEFAULT NULL , 
            typeusers INT NOT NULL DEFAULT 1 , 
            usersstat INT NOT NULL DEFAULT 1)")->setQuery();

        $this->chaine("CREATE INDEX 
                  loginusers ON useraccount (loginusers)")->setQuery();
    }

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createRecentlyActionsPostgeSQLIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
            recentactions (idrecentactions SERIAL PRIMARY KEY , 
            usersactions varchar(20)NOT NULL , 
            dateactions TIMESTAMP , 
            libactions varchar(300)NOT NULL )")->setQuery();

        $this->chaine("CREATE INDEX 
                  usersactions ON recentactions (usersactions)")->setQuery();
    }

    /**
     * Create auth_secure if not exist
     * @return void
     */
    private function CreateAuthSecurePostgeSQLIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
            authsecure (idtokensecure SERIAL PRIMARY KEY , 
            crsfauth varchar(300)NOT NULL , 
            authkey varchar(200) NOT NULL , 
            createat TIMESTAMP )")->setQuery();

        $this->chaine("CREATE INDEX 
                  crsfauth ON authsecure (crsfauth)")->setQuery();
    }

    /**
     * Create messages if not exist
     * @return void
     */
    private function CreateChatMessagesPostgeSQLIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
            chatsmessages (idchatsmessages SERIAL PRIMARY KEY , 
            emetteur varchar(20)NOT NULL , 
            destinataire varchar(20) NOT NULL , 
            typemessages int NOT NULL , 
            contentmessages varchar(500) NOT NULL , 
            datemessages TIMESTAMP , 
            etatmessages int NOT NULL)")->setQuery();

        $this->chaine("CREATE INDEX 
                    emetteur ON chatsmessages (emetteur)")->setQuery();

        $this->chaine("CREATE INDEX 
                    destinataire ON chatsmessages (destinataire)")->setQuery();
    }
}

<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait sqLiteMigrations
{

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateSqLiteUserIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS useraccount (
            idusers INTEGER PRIMARY KEY,
            loginusers TEXT NOT NULL,
            userspwd TEXT NOT NULL,
            usersname TEXT DEFAULT NULL,
            contactusers TEXT DEFAULT NULL,
            emailusers TEXT DEFAULT NULL,
            usersgroup INTEGER NOT NULL DEFAULT 1,
            usersstat INTEGER NOT NULL DEFAULT 1
        )")->setQuery();

        $this->chaine("CREATE INDEX IF NOT EXISTS loginusers ON useraccount (loginusers)")->setQuery();
    }

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createRecentlyActionsSqLiteIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
        recentactions (idrecentactions INTEGER PRIMARY KEY, 
        usersactions VARCHAR(20) NOT NULL, 
        dateactions TIMESTAMP, 
        libactions VARCHAR(300) NOT NULL)")->setQuery();

        $this->chaine("CREATE INDEX IF NOT EXISTS 
        usersactions_index ON recentactions (usersactions)")->setQuery();
    }

    /**
     * Create auth_secure if not exist
     * @return void
     */
    private function CreateAuthSecureSqLiteIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
        authsecure (idtokensecure INTEGER PRIMARY KEY, 
        crsfauth VARCHAR(300) NOT NULL, 
        authkey VARCHAR(200) NOT NULL, 
        createat TIMESTAMP)")->setQuery();

        $this->chaine("CREATE INDEX IF NOT EXISTS 
        crsfauth_index ON authsecure (crsfauth)")->setQuery();
    }

    /**
     * Create messages if not exist
     * @return void
     */
    private function CreateChatMessagesSqLiteIfNotExist():void
    {

        $this->chaine("CREATE TABLE IF NOT EXISTS 
        chatsmessages (idchatsmessages INTEGER PRIMARY KEY, 
        emetteur VARCHAR(20) NOT NULL, 
        destinataire VARCHAR(20) NOT NULL, 
        typemessages INT NOT NULL, 
        contentmessages VARCHAR(500) NOT NULL, 
        datemessages TIMESTAMP, 
        etatmessages INT NOT NULL)")->setQuery();

        $this->chaine("CREATE INDEX IF NOT EXISTS 
        emetteur_index ON chatsmessages (emetteur)")->setQuery();

        $this->chaine("CREATE INDEX IF NOT EXISTS 
        destinataire_index ON chatsmessages (destinataire)")->setQuery();
    }
}

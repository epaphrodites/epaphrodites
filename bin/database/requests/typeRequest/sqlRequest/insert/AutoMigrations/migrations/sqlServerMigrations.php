<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait sqlServerMigrations
{

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createSqlServerRecentlyActionsIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'recentactions')
                         BEGIN
                            CREATE TABLE recentactions (
                                idrecentactions int IDENTITY(1,1) NOT NULL PRIMARY KEY,
                                usersactions varchar(20) NOT NULL,
                                dateactions datetime,
                                libactions varchar(300) NOT NULL);
                            CREATE INDEX idx_usersactions ON recentactions(usersactions);
                        END")->setQuery();
    }

    /**
     * Create authsecure if not exist
     * @return void
     */
    private function CreateSqlServerAuthSecureIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'authsecure')
                        BEGIN
                            CREATE TABLE authsecure (
                                idtokensecure INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
                                crsfauth VARCHAR(300) NOT NULL,
                                authkey VARCHAR(200) NOT NULL,
                                createat DATETIME
                            );

                            CREATE INDEX idx_crsfauth ON authsecure(crsfauth);
                        END")->setQuery();
    }

    /**
     * Create messages if not exist
     * @return void
     */
    private function CreateSqlServerChatMessagesIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'chatsmessages')
                        BEGIN
                            CREATE TABLE chatsmessages (
                                idchatsmessages INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
                                emetteur VARCHAR(20) NOT NULL,
                                destinataire VARCHAR(20) NOT NULL,
                                typemessages INT NOT NULL,
                                contentmessages VARCHAR(500) NOT NULL,
                                datemessages DATETIME,
                                etatmessages INT NOT NULL
                            );

                            CREATE INDEX idx_emetteur ON chatsmessages(emetteur);
                            CREATE INDEX idx_destinataire ON chatsmessages(destinataire);
                        END")->setQuery();
    }

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateSqlServerUserIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'useraccount')
        BEGIN
            CREATE TABLE useraccount (
                idusers INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
                loginusers VARCHAR(20) NOT NULL,
                userspwd VARCHAR(100) NOT NULL,
                usersname VARCHAR(150) NULL,
                contactusers VARCHAR(10) NULL,
                emailusers VARCHAR(50) NULL,
                usersgroup INT NOT NULL DEFAULT '1',
                usersstat INT NOT NULL DEFAULT '1'
            );

            CREATE INDEX idx_loginusers ON useraccount(loginusers);
        END")->setQuery();
    }
}

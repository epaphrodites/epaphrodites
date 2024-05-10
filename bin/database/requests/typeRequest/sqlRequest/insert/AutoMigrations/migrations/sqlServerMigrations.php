<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait sqlServerMigrations
{

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createSqlServerHistoryIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'history')
                         BEGIN
                            CREATE TABLE history (
                                idhistory INTEGER IDENTITY(1,1) NOT NULL PRIMARY KEY,
                                actions VARCHAR(20) NOT NULL,
                                date DATETIME,
                                label VARCHAR(300) NOT NULL);
                            CREATE INDEX idx_actions ON history(actions);
                        END")->setQuery();
    }

    /**
     * Create authsecure if not exist
     * @return void
     */
    private function CreateSqlServerAuthSecureIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'secure')
                        BEGIN
                            CREATE TABLE secure (
                                idsecure INTEGER IDENTITY(1,1) NOT NULL PRIMARY KEY,
                                auth VARCHAR(300) NOT NULL,
                                key VARCHAR(200) NOT NULL,
                                createat DATETIME
                            );

                            CREATE INDEX idx_auth ON secure(auth);
                        END")->setQuery();
    }

    /**
     * Create user if not exist
     * @return void
     */
    private function CreateSqlServerUserIfNotExist(): void
    {

        $this->chaine("IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'usersaccount')
        BEGIN
            CREATE TABLE usersaccount (
                idusers INTEGER IDENTITY(1,1) NOT NULL PRIMARY KEY,
                login VARCHAR(20) NOT NULL,
                password VARCHAR(100) NOT NULL,
                namesurname VARCHAR(150) NULL,
                contact VARCHAR(10) NULL,
                email VARCHAR(50) NULL,
                usersgroup INTEGER NOT NULL DEFAULT '1',
                state INTEGER NOT NULL DEFAULT '1'
            );

            CREATE INDEX idx_login ON usersaccount(login);
        END")->setQuery();
    }
}

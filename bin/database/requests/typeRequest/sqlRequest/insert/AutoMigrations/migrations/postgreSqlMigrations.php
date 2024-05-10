<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert\AutoMigrations\migrations;

trait postgreSqlMigrations
{

    /**
     * Create user if not exist
     * @return void
     */
    private function CreatePostgeSQLUserIfNotExist():void
    {

        $this->multiChaine(
            [
                "CREATE TABLE IF NOT EXISTS 
                useraccount (idusers SERIAL PRIMARY KEY, 
                login VARCHAR(20) NOT NULL , 
                password VARCHAR(100) NOT NULL , 
                namesurname VARCHAR(150) DEFAULT NULL , 
                contact VARCHAR(10) DEFAULT NULL , 
                email VARCHAR(50) DEFAULT NULL , 
                group INT NOT NULL DEFAULT 1 , 
                state INT NOT NULL DEFAULT 1)", 
                "CREATE INDEX 
                    login ON useraccount (login)"

            ])->setMultiQuery();
    }

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createHistoryPostgeSQLIfNotExist():void
    {

        $this->multiChaine(
            [
                "CREATE TABLE IF NOT EXISTS 
                history (idhistory SERIAL PRIMARY KEY , 
                actions VARCHAR(20)NOT NULL , 
                date TIMESTAMP , 
                label VARCHAR(300)NOT NULL )",
                "CREATE INDEX 
                    actions ON history (actions)"

            ])->setMultiQuery();

    }

    /**
     * Create auth_secure if not exist
     * @return void
     */
    private function CreateAuthSecurePostgeSQLIfNotExist():void
    {

        $this->multiChaine(
            [
                "CREATE TABLE IF NOT EXISTS 
                secure (idsecure SERIAL PRIMARY KEY , 
                auth VARCHAR(300)NOT NULL , 
                key VARCHAR(200) NOT NULL , 
                createat TIMESTAMP )",
                "CREATE INDEX 
                    auth ON secure (auth)"

            ])->setMultiQuery();
    }
}
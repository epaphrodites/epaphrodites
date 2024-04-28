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
                loginusers VARCHAR(20) NOT NULL , 
                userspwd VARCHAR(100) NOT NULL , 
                usersname VARCHAR(150) DEFAULT NULL , 
                contactusers VARCHAR(10) DEFAULT NULL , 
                emailusers VARCHAR(50) DEFAULT NULL , 
                usersgroup INT NOT NULL DEFAULT 1 , 
                usersstat INT NOT NULL DEFAULT 1)", 
                "CREATE INDEX 
                    loginusers ON useraccount (loginusers)"

            ])->setMultiQuery();
    }

    /**
     * Create recently users actions if not exist
     * @return void
     */
    private function createRecentlyActionsPostgeSQLIfNotExist():void
    {

        $this->multiChaine(
            [
                "CREATE TABLE IF NOT EXISTS 
                recentactions (idrecentactions SERIAL PRIMARY KEY , 
                usersactions VARCHAR(20)NOT NULL , 
                dateactions TIMESTAMP , 
                libactions VARCHAR(300)NOT NULL )",
                "CREATE INDEX 
                    usersactions ON recentactions (usersactions)"

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
                authsecure (idtokensecure SERIAL PRIMARY KEY , 
                crsfauth VARCHAR(300)NOT NULL , 
                authkey VARCHAR(200) NOT NULL , 
                createat TIMESTAMP )",
                "CREATE INDEX 
                    crsfauth ON authsecure (crsfauth)"

            ])->setMultiQuery();
    }
}
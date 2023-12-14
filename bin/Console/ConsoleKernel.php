<?php

namespace Epaphrodite\Console;

class ConsoleKernel
{

    /**
     * Console commades list
     * @return array
    */
    public function GetConsolesCommands():array
    {
        return [
            new \Epaphrodite\epaphrodite\Console\commands\CommandsUsers,
            new \Epaphrodite\epaphrodite\Console\commands\CommandRunServer,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddRights,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddModules,
            new \Epaphrodite\epaphrodite\Console\commands\CommandCreatFront,
            new \Epaphrodite\epaphrodite\Console\commands\CommandController,
            new \Epaphrodite\epaphrodite\Console\commands\CommandUpdateUser,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddDatabase,
            new \Epaphrodite\epaphrodite\Console\commands\CommandRequestFiles,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddSqlRequest,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddNoSqlRequest,
            new \Epaphrodite\epaphrodite\Console\commands\CommandAddControllerFunction,
        ];
    }    
}
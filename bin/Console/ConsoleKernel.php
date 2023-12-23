<?php

namespace Epaphrodites\Console;

class ConsoleKernel
{

    /**
     * Console commades list
     * @return array
    */
    public function GetConsolesCommands():array
    {
        return [
            new \Epaphrodites\epaphrodites\Console\commands\CommandsUsers,
            new \Epaphrodites\epaphrodites\Console\commands\CommandRunServer,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddRights,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddModules,
            new \Epaphrodites\epaphrodites\Console\commands\CommandCreatFront,
            new \Epaphrodites\epaphrodites\Console\commands\CommandController,
            new \Epaphrodites\epaphrodites\Console\commands\CommandUpdateUser,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddDatabase,
            new \Epaphrodites\epaphrodites\Console\commands\CommandFirstDrivers,
            new \Epaphrodites\epaphrodites\Console\commands\CommandRequestFiles,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddSqlRequest,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddNoSqlRequest,
            new \Epaphrodites\epaphrodites\Console\commands\CommandAddControllerFunction,
        ];
    }    
}
<?php

namespace Epaphrodite\database\config\getConnexion;

use Epaphrodite\database\config\ini\GetConfig;
use Epaphrodite\database\config\Contracts\DriversConnexion;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\mysql;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\SqLite;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\mongodb;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\SqlServer;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\postgreSQL;
use Database\Epaphrodite\config\getConnexion\etablishConnexion\redisdb;

class getConnexion extends GetConfig implements DriversConnexion
{

    use mysql, postgreSQL, mongodb, SqLite , SqlServer , redisdb;
}

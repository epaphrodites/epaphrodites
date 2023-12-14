<?php

namespace Epaphrodite\epaphrodite\Console\commands;

use Epaphrodite\epaphrodite\Console\Models\createNewDatabase;

class CommandAddDatabase extends createNewDatabase{

    protected static $defaultName = 'create:db';
}
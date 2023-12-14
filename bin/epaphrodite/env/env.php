<?php

namespace Epaphrodite\epaphrodite\env;

use Epaphrodite\epaphrodite\env\pyEnv\pyEnv;
use Epaphrodite\epaphrodite\env\phpEnv\phpEnv;
use Epaphrodite\epaphrodite\env\config\GeneralConfig;
use Epaphrodite\epaphrodite\define\config\traits\currentFunctionNamespaces;

class env extends GeneralConfig
{

    use currentFunctionNamespaces, phpEnv, pyEnv;
}

<?php

namespace Epaphrodite\epaphrodite\define\config;

use Epaphrodite\epaphrodite\define\config\traits\currentStaticArray;
use Epaphrodite\epaphrodite\define\config\traits\currentSetGuardSecure;
use Epaphrodite\epaphrodite\define\config\traits\currentFunctionNamespaces;
use Epaphrodite\epaphrodite\define\config\traits\currentVariableNameSpaces;

class currentNameSpace{
    use currentFunctionNamespaces, currentVariableNameSpaces , currentStaticArray , currentSetGuardSecure;
}
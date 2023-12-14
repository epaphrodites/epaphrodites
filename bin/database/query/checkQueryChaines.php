<?php

namespace Epaphrodite\database\query;

use Epaphrodite\database\query\buildQuery\buildQuery;
use Epaphrodite\database\query\buildChaines\queryChaines;
use Epaphrodite\database\query\buildChaines\buildQueryChaines;
use Epaphrodite\epaphrodite\define\config\traits\currentFunctionNamespaces;

class checkQueryChaines{
  
    use queryChaines, buildQuery , buildQueryChaines, currentFunctionNamespaces;
}
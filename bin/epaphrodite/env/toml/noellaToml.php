<?php

namespace Epaphrodite\epaphrodite\env\toml;

use Epaphrodite\epaphrodite\env\toml\traits\delToTomlFile;
use Epaphrodite\epaphrodite\env\toml\traits\loadTomlFile;
use Epaphrodite\epaphrodite\env\toml\traits\AddToTomlFile;
use Epaphrodite\epaphrodite\env\toml\traits\readTomlFiles;

final class noellaToml{

    use loadTomlFile, readTomlFiles , AddToTomlFile, delToTomlFile;
}
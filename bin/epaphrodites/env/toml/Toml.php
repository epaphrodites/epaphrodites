<?php

namespace Epaphrodites\epaphrodites\env\toml;

use Epaphrodites\epaphrodites\env\toml\traits\delToTomlFile;
use Epaphrodites\epaphrodites\env\toml\traits\loadTomlFile;
use Epaphrodites\epaphrodites\env\toml\traits\AddToTomlFile;
use Epaphrodites\epaphrodites\env\toml\traits\readTomlFiles;

final class Toml{

    use loadTomlFile, readTomlFiles , AddToTomlFile, delToTomlFile;
}
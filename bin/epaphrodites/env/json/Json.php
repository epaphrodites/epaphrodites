<?php

namespace Epaphrodites\epaphrodites\env\json;

use Epaphrodites\epaphrodites\env\json\requests\addToJson;
use Epaphrodites\epaphrodites\env\json\requests\delJson;
use Epaphrodites\epaphrodites\env\json\requests\getJson;

final class Json{

    use addToJson, delJson, getJson;
}
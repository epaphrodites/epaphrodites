<?php

namespace Epaphrodite\database\config\Contracts;

interface Builders
{
    /**
     * @return mixed
     */
    public function checkDbType();

    /**
     *@return mixed
     */
    public static function firstSeederGeneration();
}

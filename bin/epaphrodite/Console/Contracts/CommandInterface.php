<?php

namespace Epaphrodite\Console;

interface CommandInterface
{

    /**
     * @param null|array $parameters
    */
    public function execute(?array $parameters = []);
}
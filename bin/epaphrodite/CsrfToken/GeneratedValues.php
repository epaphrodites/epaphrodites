<?php

namespace Epaphrodite\epaphrodite\CsrfToken;

use Epaphrodite\epaphrodite\CsrfToken\encryptToken\encryptTokenValue;

class GeneratedValues extends encryptTokenValue
{
    
    /**
     * @return string
     */
    public function getvalue(): string
    {
        return $this->GenerateurTokenValues(70);
    }
}

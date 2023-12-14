<?php

namespace Epaphrodites\epaphrodites\CsrfToken;

use Epaphrodites\epaphrodites\CsrfToken\encryptToken\encryptTokenValue;

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

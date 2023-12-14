<?php

declare(strict_types=1);

namespace Epaphrodite\epaphrodite\CsrfToken;

use Epaphrodite\epaphrodite\CsrfToken\csrf_secure;
use Epaphrodite\epaphrodite\CsrfToken\GeneratedValues;
use Epaphrodite\epaphrodite\CsrfToken\traits\HashVerify;
use Epaphrodite\epaphrodite\CsrfToken\errors\tokenError;

class validate_token extends GeneratedValues
{

    use HashVerify;
    
    protected object $error;
    protected object $secure;

    public function __construct()
    {
        $this->error = new tokenError;
        $this->secure = new csrf_secure;
    }

    /**
     * Get the CSRF token from POST or GET
     *
     * @return string|null
     */
    private function getInputToken(): ?string
    {
        return (!empty($_POST[CSRF_FIELD_NAME])) ? $_POST[CSRF_FIELD_NAME] : ((!empty($_GET[CSRF_FIELD_NAME])) ? $_GET[CSRF_FIELD_NAME] : null);
    }

    /**
     * Verify the CSRF token
     *
     * @return bool
     */
    public function isValidToken(): bool
    {
        
        return (static::class('session')->login() !== null) ? $this->verifyOn() : $this->verifyOff();
    }

    /**
     * Verify when CSRF is turned on
     *
     * @return bool
     */
    protected function verifyOn(): bool
    {

        $hashedSecure = static::gostHash( 
            _DATABASE_ === 'sql' ? 
            $this->secure->secure() 
            :$this->secure->noSqlSecure());

        $hashedInput = static::gostHash($this->getInputToken());

        $hashedValue = static::gostHash($this->getValue());

        if (static::verifyHashes($hashedSecure , $hashedInput , $hashedValue)) {
            return true;
        } else {
            $this->error->send();
            return false;
        }
    }

    /**
     * Verify when CSRF is turned off
     *
     * @return bool
     */
    protected function verifyOff(): bool
    {
        $hashedInput = static::gostHash($this->getInputToken());
        $hashedValue = static::gostHash($this->getValue());

        if (static::verifyInputHashes($hashedInput , $hashedValue)) {
            return true;
        } else {
            $this->error->send();
            return false;
        }
    }
}

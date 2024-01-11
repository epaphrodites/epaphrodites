<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\CsrfToken;

use Epaphrodites\epaphrodites\CsrfToken\csrf_secure;
use Epaphrodites\epaphrodites\CsrfToken\GeneratedValues;
use Epaphrodites\epaphrodites\CsrfToken\traits\HashVerify;
use Epaphrodites\epaphrodites\CsrfToken\errors\tokenError;

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

        /** 5
     * @return string
     */
    private function checkMainDriverRequest():string {

        return match (_FIRST_DRIVER_) {

            'mongo' => $this->secure->noSqlSecure(),
            'redis' => $this->secure->noSqlRedisSecure(),
  
            default => $this->secure->secure(),
      };
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

        $request = $this->checkMainDriverRequest();

        $hashedSecure = static::gostHash($request);

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

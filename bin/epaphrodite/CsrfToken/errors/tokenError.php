<?php

namespace Epaphrodite\epaphrodite\CsrfToken\errors;

use Epaphrodite\controllers\render\errors;

class tokenError extends errors{
    
    /**
     * @return void
     */
    public function send():void
    {
         $this->sendTologin();
    }
}
<?php

namespace Epaphrodites\epaphrodites\auth;

use Epaphrodites\epaphrodites\auth\SetUsersCookies;
use Epaphrodites\epaphrodites\CsrfToken\GeneratedValues;

class SetSessionSetting extends SetUsersCookies
{
    private static bool $IS_SSL;

    /**
     * Started
     * @return bool
     * @access private
     */
    private static function hasStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * set session et cookies
     * 
     * @return void
     */
    private function getSessionIfNotExist():void
    {

        $name = static::class('msg')->answers('session_name');

        if (!static::hasStarted()) {

            static::$IS_SSL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';

            if (!empty($name)) {
                session_name($name);
            } elseif (static::$IS_SSL) {
                session_name('__Secure-PHPSESSID');
            }

            $this->setting->session_params()['domain'] = $_SERVER['SERVER_NAME']??'';
            $this->setting->session_params()['secure'] = static::$IS_SSL;

            session_set_cookie_params(array_merge( $this->setting->session_params(), $this->setting->others_options()));
            session_start();

            if (static::class('session')->login() === NULL && empty(static::class('session')->token_csrf())) {
                $this->set_user_cookies((new GeneratedValues)->getvalue());
            }
        }
    }

    /**
     * @return void
     */
    public function session_if_not_exist()
    {
        return $this->getSessionIfNotExist();
    }

}

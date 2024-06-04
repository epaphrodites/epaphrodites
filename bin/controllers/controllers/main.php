<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class main extends MainSwitchers
{
    private string $ans = '';
    private string $alert = '';

    /**
     * Index page
     * @param string $html
     * @return void
     */
    public final function index(
        string $html
    ):void
    {
        $this->views($html, []);
    }
    
    /**
     * Authentification page ( login )
     * 
     * @param string $html
     * @return void
     */
    public final function login(
        string $html
    ): void
    {

        if (static::isValidMethod()) {

            $result = static::initConfig()['auth']->usersAuthManagers(
               static::getPost('__login__'),
               static::getPost('__password__')
            );

            [$this->ans, $this->alert] = static::Responses($result, [ false => ['error', 'login-wrong'] ]);
        }

        $this->views( $html,
            [
                'class' => $this->alert,
                'reponse' => $this->ans
            ]
        );
    }
}
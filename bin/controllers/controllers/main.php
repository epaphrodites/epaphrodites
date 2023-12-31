<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class main extends MainSwitchers
{
    private string $ans = '';
    private string $htmlClass = '';

    /**
     * Index page
     * @param string $html
     * @return void
     */
    public final function index(string $html): void
    {

        static::rooter()->target(_DIR_MAIN_TEMP_ . $html)->content([])->get();
    }

    /**
     * Authentification page ( login )
     * 
     * @param string $html
     * @return void
     */
    public final function login(string $html): void
    {

        if (static::isValidMethod()) {

            $result = static::initConfig()['auth']->usersAuthManagers(
               static::getPost('__codeuser__'),
               static::getPost('__password__')
            );

            if ($result === false) {
                
                $this->ans = static::initNamespace()['msg']->answers('login-wrong');
                $this->htmlClass = "error";
                
            }
        }

        static::rooter()->target(_DIR_MAIN_TEMP_ . $html)->content(
            [
                'class' => $this->htmlClass,
                'reponse' => $this->ans
            ]
        )->get();
    }
}

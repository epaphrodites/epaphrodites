<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class chats extends MainSwitchers
{
    private string $ans = '';
    private string $alert = '';
    private array|bool $result = [];

    /**
     * List of users messages.
     * Send users messages
     * Receive users messages
     *
     * @param string $html
     * @return void
     */
    public final function listOfMessages(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [],
            true
        )->get();
    }
}

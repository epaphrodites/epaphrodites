<?php

namespace Epaphrodite\controllers\controllers;

use Epaphrodite\controllers\switchers\MainSwitchers;

final class chats extends MainSwitchers
{

    private string $alert = '';
    private string $ans = '';
    private array|bool $result = [];

    /**
     * List of users messages.
     * Send users messages
     * Receive users messages
     *
     * @param string $html
     * @return mixed
     */
    public function listOfMessages(string $html): void
    {

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [],
            true
        )->get();
    }
}

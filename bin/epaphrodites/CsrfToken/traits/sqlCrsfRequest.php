<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\CsrfToken\traits;

trait sqlCrsfRequest
{

    /**
     * Update token into database
     *
     * @param string $cookies
     * @return void
     */
    private function UpdateUserCrsfToken(?string $cookies = null): void
    {

        $this->table('secure')
            ->set(['key', 'createat'])
            ->where('auth')
            ->param([$cookies,  date("Y-m-d H:i:s"), md5(static::initNamespace()['session']->login())])
            ->UQuery();
    }

    /**
     * Insert token into database
     *
     * @param string $cookies
     * @return bool
     */
    private function CreateUserCrsfToken(?string $cookies = null): bool
    {

        $this->table('secure')
            ->insert('auth , key , createat')
            ->values(' ? , ? , ?')
            ->param([md5(static::initNamespace()['session']->login()), $cookies, date("Y-m-d H:i:s")])
            ->IQuery();

        return false;
    }

    /**
     *  Check token date
     * @return string|int
     */
    public function CheckUserCrsfToken(): string|int
    {

        $addDay = 1;
        $currentDate = date('Y-m-d');

        $startOfDay = $currentDate . " 23:59:59";
        $endOfDay = $currentDate . " 23:59:59";

        $currentDate = new \DateTime(date('Y-m-d'));
        $currentDate->add(new \DateInterval("P{$addDay}D"));

        $endOfDay = $currentDate->format('Y-m-d') . " 23:59:59";

        $result = $this->table('secure')
            ->between('createat')
            ->and(['auth'])
            ->param([$startOfDay, $endOfDay, md5(static::initNamespace()['session']->login())])
            ->SQuery('key');

        return !empty($result) ? $result[0]['key'] : 0;
    }

    /**
     * Get csrf value
     *
     * @return string|int
     */
    public function secure(): string|int
    {
        $login = static::initNamespace()['session']->login();
        
        $login = $login !== null ? md5($login) : NULL;

        $result = $this->table('secure')
            ->where('auth')
            ->param([$login])
            ->SQuery();

        return !empty($result) ? $result[0]['key'] : 0;
    }
}

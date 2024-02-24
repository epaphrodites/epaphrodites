<?php

declare(strict_types=1);

namespace Epaphrodites\epaphrodites\CsrfToken\traits;

trait noSqlCrsfRequest
{

    /**
     * Get csrf value
     * @return string|int
     */
    public function noSqlSecure(): string|int
    {

        $login = static::initNamespace()['session']->login();
        
        $login = $login !== null ? md5($login) : NULL;

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('authsecure')
            ->find(['crsfauth' => $login]);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return !empty($documents) ? $documents[0]['authkey'] : 0;
    }

    /**
     * Get csrf value
     * @return string|int
     */
    public function noSqlRedisSecure(): string|int
    {

        $result = $this->key('authsecure')
            ->index(md5(static::initNamespace()['session']->login()))
            ->redisGet();

        return !empty($result) ? $result[0]['authkey'] : 0;
    }

    /**
     * Insert token into database
     *
     * @param string|null $cookies
     * @return bool
     */
    private function noSqlCreateUserCrsfToken(?string $cookies = null): bool
    {

        $document = [
            'crsfauth' => md5(static::initNamespace()['session']->login()),
            'authkey' => $cookies,
            'createat' => date("Y-m-d H:i:s"),
        ];

        $this->db(1)->selectCollection('authsecure')->insertOne($document);

        return false;
    }

    /**
     * Insert token into database
     *
     * @param string|null $cookies
     * @return bool
     */
    private function noSqlRedisCreateUserCrsfToken(?string $cookies = null): bool
    {

        $datas = [
            'crsfauth' => md5(static::initNamespace()['session']->login()),
            'authkey' => $cookies,
            'createat' => date("Y-m-d H:i:s"),
        ];

        $this->key('authsecure')->id('idtokensecure')->index(md5(static::initNamespace()['session']->login()))->param($datas)->addToRedis();

        return false;
    }

    /**
     * Update token into database
     *
     * @param string $cookies
     * @return void
     */
    private function noSqlUpdateUserCrsfToken(?string $cookies = null): void
    {

        $filter = ['crsfauth' => md5(static::initNamespace()['session']->login())];

        $update = [
            '$set' => [
                'authkey' => $cookies,
                'createat' => date("Y-m-d H:i:s"),
            ],
        ];

        $this->db(1)->selectCollection('authsecure')->updateOne($filter, $update);
    }

    /**
     * Update token into database
     *
     * @param string $cookies
     * @return void
     */
    private function noSqlRedisUpdateUserCrsfToken(?string $cookies = null): void
    {

        $index = md5(static::initNamespace()['session']->login());

        $datas =
            [
                'authkey' => $cookies,
                'createat' => date("Y-m-d H:i:s"),
            ];

        $this->key('authsecure')->index($index)->rset($datas)->updRedis();
    }

    /**
     * Check token date
     * @return string|int
     */
    public function noSqlCheckUserCrsfToken(): string|int
    {

        $addDay = 1;
        $currentDate = date('Y-m-d');

        $startOfDay = $currentDate . " 23:59:59";
        $endOfDay = $currentDate . " 23:59:59";

        $currentDate = new \DateTime(date('Y-m-d'));
        $currentDate->add(new \DateInterval("P{$addDay}D"));

        $endOfDay = $currentDate->format('Y-m-d') . " 23:59:59";

        $filter = [
            'createat' => [
                '$gte' => $startOfDay,
                '$lte' => $endOfDay,
            ],
            'crsfauth' => md5(static::initNamespace()['session']->login()),
        ];

        $result = $this->db(1)->selectCollection('authsecure')->find($filter);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return !empty($documents) ? $documents[0]['authkey'] : 0;
    }

    /**
     * Check token date
     * @return string|int
     */
    public function noSqlRedisCheckUserCrsfToken(): string|int
    {

        $addDay = 1;
        $verifyResult = 0;
        $currentDate = date('Y-m-d');

        $startOfDay = $currentDate . " 23:59:59";
        $endOfDay = $currentDate . " 23:59:59";

        $currentDate = new \DateTime(date('Y-m-d'));
        $currentDate->add(new \DateInterval("P{$addDay}D"));

        $endOfDay = $currentDate->format('Y-m-d') . " 23:59:59";

        $result = $this->key('authsecure')
            ->index(md5(static::initNamespace()['session']->login()))
            ->redisGet();

        if (!empty($result)) {
            $mainDay = $result[0]['authkey'];

            $verifyResult = match (true) {
                ($mainDay >= $startOfDay && $mainDay <= $endOfDay) => $mainDay,
                default => 0
            };
        }

        return $verifyResult;
    }
}

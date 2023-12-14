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

        $documents = [];

        $result = $this->db(1)
            ->selectCollection('authsecure')
            ->find(['crsfauth' => md5(static::initNamespace()['session']->login())]);

        foreach ($result as $document) {
            $documents[] = $document;
        }

        return !empty($documents) ? $documents[0]['authkey'] : 0;
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
}

<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert;

use Epaphrodites\database\query\Builders;

class setting extends Builders
{

    /**
     * To record recent actions
     * 
     * @param string|null $action
     * @return bool
     */
    public function ActionsRecente(
        ?string $action = null
    ): bool
    {

        $this->table('recentactions ')
            ->insert('usersactions , dateactions , libactions')
            ->values(' ? , ? , ? ')
            ->param([static::initNamespace()['session']->login(), date("Y-m-d H:i:s"), $action])
            ->IQuery();

        return true;
    }

    /**
     * To record recent actions
     * 
     * @param string|null $action
     * @return bool
     */
    public function noSqlActionsRecente(
        ?string $action = null
    ): bool
    {

        $document =
            [
                'usersactions' => static::initNamespace()['session']->login(),
                'dateactions' => date("Y-m-d H:i:s"),
                'libactions' => $action,
            ];

        $this->db(1)->selectCollection('recentactions')->insertOne($document);

        return true;
    }

    /**
     * To record recent actions
     * 
     * @param string|null $action
     * @return bool
     */
    public function noSqlRedisActionsRecente(
        ?string $action = null
    ): bool
    {

        $login = static::initNamespace()['session']->login();

        $document =
            [
                'usersactions' => $login,
                'dateactions' => date("Y-m-d H:i:s"),
                'libactions' => $action,
            ];

        $this->key('recentactions')->id('idrecentactions')->index($login)->param($document)->lastIndex()->addToRedis();

        return true;
    }
}
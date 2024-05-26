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

        $this->table('history ')
            ->insert('actions , dates , label')
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
                'actions' => static::initNamespace()['session']->login(),
                'dates' => date("Y-m-d H:i:s"),
                'label' => $action,
            ];

        $this->db(1)->selectCollection('history')->insertOne($document);

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
                'actions' => $login,
                'dates' => date("Y-m-d H:i:s"),
                'label' => $action,
            ];

        $this->key('history')->id('idhistory')->index($login)->param($document)->lastIndex()->addToRedis();

        return true;
    }
}
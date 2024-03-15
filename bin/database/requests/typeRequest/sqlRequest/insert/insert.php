<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\insert\insert as InsertInsert;

class insert extends InsertInsert
{

    /**
     * Add users to the system from the console
     *
     * @param string|null $login
     * @param string|null $password
     * @param int|null $UserGroup
     * @return bool
     */
    public function sqlConsoleAddUsers(
        ?string $login = null, 
        ?string $password = null, 
        ?int $UserGroup = null
    ):bool
    {

        $UserGroup = $UserGroup !== NULL ? $UserGroup : 1;

        if (!empty($login) && count(static::initQuery()['getid']->sqlGetUsersDatas($login)) < 1) {

            $this->table('useraccount')
                ->insert(' loginusers , userspwd , usersgroup ')
                ->values(' ? , ? , ? ')
                ->param([static::initNamespace()['env']->no_space($login), static::initConfig()['guard']->CryptPassword($password), $UserGroup])
                ->IQuery();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Add chats
     * 
     * @param string|null $emitter
     * @param string|null $recipient
     * @param int|null $type
     * @param string|null $content
     * @return bool
     */
    public function addUserChats(
        ?string $emitter = null, 
        ?string $recipient = null, 
        ?int $type = null, 
        ?string $content = null
    ):bool
    {

        if (!empty($content) && !empty($recipient)) {

            $this->table('chatsmessages')
                ->insert(' emetteur , destinataire , typemessages , datemessages , contentmessages ')
                ->values(' ? , ? , ? , ? , ? ')
                ->param([static::initNamespace()['env']->no_space($emitter), static::initNamespace()['env']->no_space($recipient), $type, date("Y-m-d H:i:s"), $content])
                ->IQuery();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Add users to the system
     *
     * @param string|null $login
     * @param int|null $usersgroup
     * @return bool
     */
    public function sqlAddUsers(
        ?string $login = null, 
        ?int $usersgroup = null
    ):bool
    {

        if (!empty($login) && !empty($usersgroup) && count(static::initQuery()['getid']->sqlGetUsersDatas($login)) < 1) {

            $this->table('useraccount')
                ->insert(' loginusers , userspwd , usersgroup ')
                ->values(' ? , ? , ? ')
                ->param([static::initNamespace()['env']->no_space($login), static::initConfig()['guard']->CryptPassword($login . '@'), $usersgroup])
                ->IQuery();

            $actions = "Add a User : " . $login;
            static::initQuery()['setting']->ActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }   
}

<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\insert;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\insert\insert as InsertInsert;

class insert extends InsertInsert
{

    /**
     * Add users to the system from the console
     *
     * @param string|null $login
     * @param int|null $idtype
     * @return bool
     */
    public function sqlConsoleAddUsers(?string $login = null, ?string $password = null, ?int $UserGroup = null):bool
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
     * @param string|null $login
     * @param int|null $type
     * @param int|null $request
     * @param string|null $content
     * @return bool
     */
    public function addUserChats(?string $emetteur = null, ?string $destinataire = null, ?int $type = null, ?string  $content = null):bool
    {

        if (!empty($content) && !empty($destinataire)) {

            $this->table('chatsmessages')
                ->insert(' emetteur , destinataire , typemessages , datemessages , contentmessages ')
                ->values(' ? , ? , ? , ? , ? ')
                ->param([static::initNamespace()['env']->no_space($emetteur), static::initNamespace()['env']->no_space($destinataire), $type, date("Y-m-d H:i:s"), $content])
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
     * @param int|null $idtype
     * @return bool
     */
    public function sqlAddUsers(?string $login = null, ?int $idtype = null):bool
    {

        if (!empty($login) && !empty($idtype) && count(static::initQuery()['getid']->sqlGetUsersDatas($login)) < 1) {

            $this->table('useraccount')
                ->insert(' loginusers , userspwd , usersgroup ')
                ->values(' ? , ? , ? ')
                ->param([static::initNamespace()['env']->no_space($login), static::initConfig()['guard']->CryptPassword($login . '@'), $idtype])
                ->IQuery();

            $actions = "Add a User : " . $login;
            static::initQuery()['setting']->ActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }   
}

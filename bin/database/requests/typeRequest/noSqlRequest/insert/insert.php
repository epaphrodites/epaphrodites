<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert;

use MongoDB\BSON\ObjectId;
use Epaphrodites\database\query\Builders;

class insert extends Builders
{

    /**
     * Add users to the system from the console
     *
     * @param string|null $login
     * @param int|null $idtype
     * @return bool
     */
    public function noSqladdUsers(?string $login = null, ?int $idtype = null)
    {

        if (!empty($login) && !empty($idtype) && count(static::initQuery()['getid']->noSqlGetUsersDatas($login)) < 1) {

            $document = [
                'idusers' => new ObjectId(),
                'loginusers' => $login,
                'userspwd' => static::initConfig()['guard']->CryptPassword($login),
                'usersname' => NULL,
                'contactusers' => NULL,
                'emailusers' => NULL,
                'usersstat' => 1,
                'usersgroup' => $idtype,
            ];

            $this->db(1)->selectCollection('useraccount')->insertOne($document);

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlActionsRecente($actions);

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
    public function noSqlConsoleAddUsers(?string $login = null, ?string $password = null, ?int $UserGroup = null)
    {

        $UserGroup = $UserGroup !== NULL ? $UserGroup : 1;
      
        if (!empty($login) && count(static::initQuery()['getid']->noSqlGetUsersDatas($login)) < 1) {

            $document = [
                'idusers' => new ObjectId(),
                'loginusers' => $login,
                'userspwd' => static::initConfig()['guard']->CryptPassword($password),
                'usersname' => NULL,
                'contactusers' => NULL,
                'emailusers' => NULL,
                'usersstat' => 1,
                'usersgroup' => $UserGroup,
            ];
            
            $this->db(1)->selectCollection('useraccount')->insertOne($document);

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }
}

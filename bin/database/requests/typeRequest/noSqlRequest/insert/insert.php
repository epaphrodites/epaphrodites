<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert;

use MongoDB\BSON\ObjectId;
use Epaphrodites\database\query\Builders;

class insert extends Builders
{

    /**
     * Ajouter des droits utilisateurs dans le systeme
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
                'nomprenomsusers' => NULL,
                'contactusers' => NULL,
                'emailusers' => NULL,
                'usersstat' => 1,
                'typeusers' => $idtype,
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
     * Ajouter des utilisateurs dans le systeme a partir de la console
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
                'nomprenomsusers' => NULL,
                'contactusers' => NULL,
                'emailusers' => NULL,
                'usersstat' => 1,
                'typeusers' => $UserGroup,
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

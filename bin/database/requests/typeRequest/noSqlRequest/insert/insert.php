<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\insert;

use Epaphrodites\database\query\Builders;

class insert extends Builders
{

    /**
     * Add users to the system from the console
     *
     * @param string|null $login
     * @param int|null $userGroup
     * @return bool
     */
    public function noSqladdUsers(
        ?string $login = null,
        ?int $userGroup = null
    ):bool
    {

        if (!empty($login) && !empty($userGroup) && count(static::initQuery()['getid']->noSqlGetUsersDatas($login)) < 1) {

            $document = [
                'login' => $login,
                'password' => static::initConfig()['guard']->CryptPassword($login),
                'namesurname' => NULL,
                'contact' => NULL,
                'email' => NULL,
                'usersgroup' => $userGroup,
                'state' => 1,
            ];

            $this->db(1)->selectCollection('usersaccount')->insertOne($document);

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Create user if not exist
     */
    public function noSqlRedisAddUsers(
        ?string $login = null,
        ?int $userGroup = null
    ):bool{
        
        if (!empty($login) && !empty($userGroup) && count(static::initQuery()['getid']->noSqlRedisGetUsersDatas($login)) < 1) {

            $document =[
                'login' => $login,
                'password' => static::initConfig()['guard']->CryptPassword($login),
                'namesurname' => NULL,
                'contact' => NULL,
                'email' => NULL,
                'usersgroup' => $userGroup,
                'state' => 1,
            ];

            $this->key('usersaccount')->id('_id')->index($login)->param($document)->addToRedis();

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlRedisActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }  

    /**
     * Add users to the system
     *
     * @param string|null $login
     * @param string|null $password
     * @param int|null $userGroup
     * @return bool
     */
    public function noSqlConsoleAddUsers(
        ?string $login = null, 
        ?string $password = null, 
        ?int $userGroup = null
    ):bool
    {

        $userGroup = $userGroup !== NULL ? $userGroup : 1;
      
        if (!empty($login) && count(static::initQuery()['getid']->noSqlGetUsersDatas($login)) < 1) {

            $document = [
                'login' => $login,
                'password' => static::initConfig()['guard']->CryptPassword($password),
                'namesurname' => NULL,
                'contact' => NULL,
                'email' => NULL,
                'usersgroup' => $userGroup,
                'state' => 1
            ];
            
            $this->db(1)->selectCollection('usersaccount')->insertOne($document);

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }

   /**
     * Create user if not exist
     */
    public function noSqlRedisConsoleAddUsers(
        ?string $login = null, 
        ?string $password = null, 
        ?int $userGroup = null
    ):bool{
        
        $userGroup = $userGroup !== NULL ? $userGroup : 1;

        if (!empty($login) && count(static::initQuery()['getid']->noSqlRedisGetUsersDatas($login)) < 1) {

            $document =[
                'login' => $login,
                'password' => static::initConfig()['guard']->CryptPassword($password),
                'namesurname' => NULL,
                'contact' => NULL,
                'email' => NULL,
                'usersgroup' => $userGroup,
                'state' => 1,
            ];

            $this->key('usersaccount')->id('_id')->index($login)->param($document)->addToRedis();

            $actions = "Add a user : " . $login;
            static::initQuery()['setting']->noSqlRedisActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }      
}

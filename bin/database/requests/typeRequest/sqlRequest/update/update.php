<?php

namespace Epaphrodites\database\requests\typeRequest\sqlRequest\update;

use Epaphrodites\database\requests\typeRequest\noSqlRequest\update\update as UpdateUpdate;

class update extends UpdateUpdate
{

    /**
     * Request to update chat messages
     * 
     * @param string|null $users
     * @return bool
     */
    public function chat_messages(string $users): bool
    {
        $this->table('chatsmessages')
            ->set(['etatmessages'])
            ->where('emetteur')
            ->and(['destinataire', 'etatmessages'])
            ->param([0, $users, static::initNamespace()['session']->login(), 1])
            ->UQuery();

        return true;
    }

    /**
     * Request to update users password
     *
     * @param string $OldPassword
     * @param string $NewPassword
     * @param string $confirmdp
     * @return int|bool
     */
    public function sqlChangeUsersPassword($OldPassword, $NewPassword, $confirmdp):int|bool
    {

        if (static::initConfig()['guard']->GostCrypt($NewPassword) === static::initConfig()['guard']->GostCrypt($confirmdp)) {

            $result = static::initQuery()['auth']->findSqlUsers(static::initNamespace()['session']->login());

            if (!empty($result)) {

                if (static::initConfig()['guard']->AuthenticatedPassword($result[0]["userspwd"], $OldPassword) === true) {

                    $this->table('useraccount')
                        ->set(['userspwd'])
                        ->where('idusers')
                        ->param([static::initConfig()['guard']->CryptPassword($NewPassword), static::initNamespace()['session']->id()])
                        ->UQuery();

                    $actions = "Change password : " . static::initNamespace()['session']->login();
                    static::initQuery()['setting']->ActionsRecente($actions);

                    $this->desconnect = static::initNamespace()['paths']->logout();

                    header("Location: $this->desconnect ");
                    exit;
                } else {
                    return 3;
                }
            } else {

                return 2;
            }
        } else {
            return 1;
        }
    }

    /**
     * Update user password and user group
     *
     * @param integer $login
     * @param string|NULL $password
     * @param int|NULL $UserGroup
     * @return bool
     */
    public function sqlConsoleUpdateUsers(?string $login = null, ?string $password = NULL, ?int $UserGroup = NULL): bool
    {
        $GetDatas = static::initQuery()['getid']->sqlGetUsersDatas($login);

        if (!empty($GetDatas)) {

            $password = $password !== NULL ? $password : $login;
            $UserGroup = $UserGroup !== NULL ? $UserGroup : $GetDatas[0]['usersgroup'];

            $this->table('useraccount')
                ->set(['userspwd', 'usersgroup'])
                ->where('loginusers')
                ->param([static::initConfig()['guard']->CryptPassword($password), $UserGroup, "$login"])
                ->UQuery();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Request to initialize user password
     *
     * @param integer $UsersLogin
     * @return bool
     */
    public function sqlInitUsersPassword(string $UsersLogin): bool
    {

        $this->table('useraccount')
            ->set(['userspwd'])
            ->where('loginusers')
            ->param([static::initConfig()['guard']->CryptPassword($UsersLogin), $UsersLogin])
            ->UQuery();

        $actions = "Reset user password : " . $UsersLogin;
        static::initQuery()['setting']->ActionsRecente($actions);

        return true;
    }

    /**
     * Request to switch user connexion state
     *
     * @param integer $login
     * @return bool
     */
    public function sqlUpdateEtatsUsers(string $login): bool
    {
        
        $GetUsersDatas = static::initQuery()['getid']->sqlGetUsersDatas($login);

        if (!empty($GetUsersDatas)) {

            $state = !empty($GetUsersDatas[0]['usersstat']) ? 0 : 1;

            $etatExact = "Close";

            if ($state == 1) {
                $etatExact = "Open";
            }

            $this->table('useraccount')
                ->set(['usersstat'])
                ->like('loginusers')
                ->param([$state, $GetUsersDatas[0]['loginusers']])
                ->UQuery();
              
            $actions = $etatExact . " of the user's account : " . $GetUsersDatas[0]['loginusers'];
            static::initQuery()['setting']->ActionsRecente($actions);
           
            return true;
        } else {
            return false;
        }
    }

    /**
     * Request to update user datas
     *
     * @param string $usersname
     * @param string $email
     * @param string $number
     * @return bool
     */
    public function sqlUpdateUserDatas(string $usersname, string $email, string $number)
    {
        
        if (static::initNamespace()['verify']->onlyNumber($number, 11) === false) {

            $this->table('useraccount')
                ->set(['contactusers', 'emailusers', 'usersname', 'usersstat'])
                ->where('idusers')
                ->param([$number, $email, $usersname, 1, static::initNamespace()['session']->id()])
                ->UQuery();

            $_SESSION["usersname"] = $usersname;

            $_SESSION["contact"] = $number;

            $_SESSION["email"] = $email;

            $actions = "Edit Personal Information : " . static::initNamespace()['session']->login();
            static::initQuery()['setting']->ActionsRecente($actions);

            $this->desconnect = static::initNamespace()['paths']->dashboard();

            header("Location: $this->desconnect ");
            exit;
        } else {
            return false;
        }
    }
}

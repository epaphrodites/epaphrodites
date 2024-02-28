<?php

namespace Epaphrodites\database\requests\typeRequest\noSqlRequest\update;

use Epaphrodites\database\query\Builders;

class update extends Builders
{
    protected $desconnect;

    /**
     * Update users informations
     *
     * @param string $usersname
     * @param string $email
     * @param string $number
     * @return bool
     */
    public function noSqlUpdateUserDatas(string $usersname, string $email, string $number): bool
    {

        $filter = [
            'loginusers' => static::initNamespace()['session']->login(),
        ];

        $update = [
            '$set' => 
            [
                'contactusers' => $number,
                'emailusers' => $email,
                'usersname' => $usersname,
                'usersstat' => 1,
            ],
        ];   

        $this->db(1)->selectCollection('useraccount')->updateMany($filter, $update);

        if (static::initNamespace()['verify']->onlyNumber($number, 11) === false) {


            $_SESSION["usersname"] = $usersname;

            $_SESSION["contact"] = $number;

            $_SESSION["email"] = $email;
            
            $actions = "Edit Personal Information : " . static::initNamespace()['session']->login();
            static::initQuery()['setting']->noSqlActionsRecente($actions);            

            $this->desconnect = static::initNamespace()['paths']->dashboard();
            header("Location: $this->desconnect ");
            exit;
        } else {
            return false;
        }                    
    }  

   /**
     * Update users informations
     *
     * @param string $usersname
     * @param string $email
     * @param string $number
     * @return bool
     */
    public function noSqlRedisUpdateUserDatas(string $usersname, string $email, string $number): bool
    {

        $login = static::initNamespace()['session']->login();

        $datas = [
                'contactusers' => $number,
                'emailusers' => $email,
                'usersname' => $usersname,
                'usersstat' => 1,
        ];   

        $this->key('useraccount')->index($login)->rset($datas)->updRedis();

        if (static::initNamespace()['verify']->onlyNumber($number, 11) === false) {

            $_SESSION["usersname"] = $usersname;

            $_SESSION["contact"] = $number;

            $_SESSION["email"] = $email;
            
            $actions = "Edit Personal Information : " . static::initNamespace()['session']->login();
            static::initQuery()['setting']->noSqlRedisActionsRecente($actions);            

            $this->desconnect = static::initNamespace()['paths']->dashboard();
            header("Location: $this->desconnect ");
            exit;
        } else {
            return false;
        }                    
    }      
    
    /**
     * Update users state
     *
     * @param integer $type_user
     * @param integer $id_user
     * @return bool
     */
    public function noSqlUpdateEtatsUsers(string $login): bool
    {

        $GetUsersDatas = static::initQuery()['getid']->noSqlGetUsersDatas($login);

        if (!empty($GetUsersDatas)) {

            $state = !empty($GetUsersDatas[0]['usersstat']) ? 0 : 1;

            $etatExact = "Close";

            if ($state == 1) {
                $etatExact = "Open";
            }

            $filter = [ 'loginusers' => $GetUsersDatas[0]['loginusers'] ];
    
            $update = [
                '$set' => [ 'usersstat' => $state ]
            ];   
    
            $this->db(1)->selectCollection('useraccount')->updateMany($filter, $update);

            $actions = $etatExact . " of the user's account : " . $GetUsersDatas[0]['loginusers'];
            static::initQuery()['setting']->noSqlActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }   

    /**
     * Reinitialize user password
     *
     * @param integer $type_user
     * @param integer $id_user
     * @return bool
     */
    public function noSqlInitUsersPassword(string $UsersLogin): bool
    {

        $filter = [ 'loginusers' => $UsersLogin ];
    
        $update = [
            '$set' => [ 'userspwd' => static::initConfig()['guard']->CryptPassword($UsersLogin . '@') ]
        ];   

        $this->db(1)->selectCollection('useraccount')->updateMany($filter, $update);

        $actions = "Reset user password : " . $UsersLogin;
        static::initQuery()['setting']->noSqlActionsRecente($actions);

        return true;
    }  
    
    /**
     * Update user password
     *
     * @param string $ancienmdp
     * @param string $newmdp
     * @param string $confirmdp
     * @return bool
     */
    public function noSqlChangeUsersPassword( $OldPassword, $NewPassword, $confirmdp): bool
    {

        if (static::initConfig()['guard']->GostCrypt($NewPassword) === static::initConfig()['guard']->GostCrypt($confirmdp)) {

            $result = static::initQuery()['auth']->findNosqlUsers( static::initNamespace()['session']->login() );

            if (!empty($result)) {

                if (static::initConfig()['guard']->AuthenticatedPassword($result[0]["userspwd"], $OldPassword) === true) {

                    $filter = [ 'loginusers' => static::initNamespace()['session']->login() ];
    
                    $update = [
                        '$set' => [ 'userspwd' => static::initConfig()['guard']->CryptPassword($NewPassword) ]
                    ];   
            
                    $this->db(1)->selectCollection('useraccount')->updateMany($filter, $update);
            
                    $actions = "Change password : " . static::initNamespace()['session']->login() ;
                    static::initQuery()['setting']->noSqlActionsRecente($actions);

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
    public function noSqlConsoleUpdateUsers(?string $login = null, ?string $password = NULL, ?int $UserGroup = NULL): bool
    {
        $GetDatas = static::initQuery()['getid']->noSqlGetUsersDatas($login);

        if (!empty($GetDatas)) {

            $password = $password !== NULL ? $password : $login;
            $UserGroup = $UserGroup !== NULL ? $UserGroup : $GetDatas[0]['usersgroup'];

            $filter = [ 'loginusers' => $login ];
    
            $update = [
                '$set' => [ 'userspwd' => static::initConfig()['guard']->CryptPassword($password), 'usersgroup' => $UserGroup ]
            ];   
    
            $this->db(1)->selectCollection('useraccount')->updateMany($filter, $update);
    
            $actions = "Edit Personal Information : " . $login ;
            static::initQuery()['setting']->noSqlActionsRecente($actions);

            return true;
        } else {
            return false;
        }
    }

}
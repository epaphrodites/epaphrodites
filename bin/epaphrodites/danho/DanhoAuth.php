<?php

namespace Epaphrodites\epaphrodites\danho;

use Epaphrodites\epaphrodites\auth\StartUsersSession;

class DanhoAuth extends StartUsersSession
{

  /**
   **
   * Verify authentification of user
   * @param string $login
   * @param string $usersPassword
   * @return bool
   */
  private function getUsersAuthManagers(string $login, string $usersPassword):bool
  {

    if(!empty($login)&&!empty($usersPassword)){

    if ((static::class('verify')->onlyNumberAndCharacter($login, 12)) === false) {

      $result = static::getGuard('sql')->checkUsers($login);

      if (!empty($result)) {

          if (static::getGuard('guard')->AuthenticatedPassword($result[0]["userspwd"], $usersPassword) === true && $result[0]["usersstat"] === 1) {
            
            $this->StartUsersSession($result[0]["idusers"], $result[0]["loginusers"], $result[0]["usersname"], $result[0]["contactusers"], $result[0]["emailusers"], $result[0]["usersgroup"]);
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
    }else{
      return false;
    }
  }

  /**
   **
   * Verify authentification of user
   * @param string $login
   * @param string $usersPassword
   * @return bool
   */  
  public function UsersAuthManagers(string $login, string $usersPassword):bool
  {
    return $this->getUsersAuthManagers($login, $usersPassword);
  }
}

<?php

namespace Epaphrodites\epaphrodites\danho;

use Epaphrodites\epaphrodites\auth\StartUsersSession;

class DanhoAuth extends StartUsersSession
{

  use \Epaphrodites\epaphrodites\env\phpEnv\phpEnv;

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

          if (static::getGuard('guard')->AuthenticatedPassword($result[0]["password"], $usersPassword) == true && $result[0]["state"] == 1) {
           
            $this->StartUsersSession($result[0]["_id"], $result[0]["login"], $result[0]["namesurname"], $result[0]["contact"], $result[0]["email"], $result[0]["usersgroup"]);
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

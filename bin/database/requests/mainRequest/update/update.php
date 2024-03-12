<?php

namespace Epaphrodites\database\requests\mainRequest\update;

use Epaphrodites\database\requests\typeRequest\sqlRequest\update\update as UpdateUpdate;

final class update extends UpdateUpdate
{

  /**
   * Request to update users rights
   * 
   * @param int|null $userGroup
   * @param int|null $etat
   * @return bool
   */
  public function updateUserRights(
    ?string $userGroup = null, 
    ?int $state = null
  ): bool{

    return static::initConfig()['updright']->UpdateUsersRights($userGroup, $state) === true ? true : false;
  }

  /**
   * Request to update users informations
   * 
   * @param string $userName
   * @param string $email
   * @param string $number
   * @return bool
   */
  public function updateUserDatas(
    string $userName,
    string $email,
    string $number
  ): bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlUpdateUserDatas($userName, $email, $number),
      'redis' => $this->noSqlRedisUpdateUserDatas($userName, $email, $number),

      default => $this->sqlUpdateUserDatas($userName, $email, $number),
    };
  }

  /**
   * Request to update users state
   * 
   * @param string $login
   * @return bool
   */
  public function updateEtatsUsers(
    string $login
  ): bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlUpdateEtatsUsers($login),
      'redis' => $this->noSqlUpdateEtatsUsers($login),

      default => $this->sqlUpdateEtatsUsers($login),
    };
  }

  /**
   * Request to init users password
   * 
   * @param string $login
   * @return bool
   */
  public function initUsersPassword(
    string $login
  ): bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlInitUsersPassword($login),
      'redis' => $this->noSqlInitUsersPassword($login),

      default => $this->sqlInitUsersPassword($login),
    };
  }

  /**
   * Change users password
   *
   * @param string $oldPassword
   * @param string $newPassword
   * @param string $confirMdp
   * @return array
   */
  public function changeUsersPassword(
    string $oldPassword,
    string $newPassword,
    string $confirMdp
  ): bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp),
      'redis' => $this->noSqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp),

      default => $this->sqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp)
    };
  }

  /**
   * Request to update users datas from console
   *
   * @param string $login
   * @param string $password
   * @param string $UserGroup
   * @return bool
   */
  public function ConsoleUpdateUsers(
    string $login,
    string $password,
    int $userGroup
  ): bool{

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlConsoleUpdateUsers($login, $password, $userGroup),
      'redis' => $this->noSqlConsoleUpdateUsers($login, $password, $userGroup),

      default => $this->sqlConsoleUpdateUsers($login, $password, $userGroup)
    };
  }
}
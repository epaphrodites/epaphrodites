<?php

namespace Epaphrodites\database\requests\mainRequest\update;

use Epaphrodites\database\requests\typeRequest\sqlRequest\update\update as UpdateUpdate;

final class update extends UpdateUpdate
{

  /**
   * Request to update users rights
   * 
   * @param int|null $idtype_user
   * @param int|null $etat
   * @return bool
   */
  public function updateUserRights(?string $IdTypeUsers = null, ?int $etat = null): bool
  {

    return static::initConfig()['updright']->UpdateUsersRights($IdTypeUsers, $etat) === true ? true : false;
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return bool
   */
  public function updateUserDatas(string $nomprenoms, string $email, string $number): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlUpdateUserDatas($nomprenoms, $email, $number),
      'redis' => $this->noSqlRedisUpdateUserDatas($nomprenoms, $email, $number),

      default => $this->sqlUpdateUserDatas($nomprenoms, $email, $number),
    };
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return bool
   */
  public function updateEtatsUsers(string $login): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlUpdateEtatsUsers($login),
      'redis' => $this->noSqlUpdateEtatsUsers($login),

      default => $this->sqlUpdateEtatsUsers($login),
    };
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return bool
   */
  public function initUsersPassword(string $login): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlInitUsersPassword($login),
      'redis' => $this->noSqlInitUsersPassword($login),

      default => $this->sqlInitUsersPassword($login),
    };
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @param string $newPassword
   * @param string $confirMdp
   * @return array
   */
  public function changeUsersPassword(string $oldPassword, string $newPassword, string $confirMdp): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp),
      'redis' => $this->noSqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp),

      default => $this->sqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp)
    };
  }

  /**
   * Verify if exist in database
   *
   * @param string $login
   * @param string $password
   * @param string $UserGroup
   * @return bool
   */
  public function ConsoleUpdateUsers(?string $login = null, ?string $password = NULL, ?int $userGroup = NULL): bool
  {

    return match (_FIRST_DRIVER_) {

      'mongo' => $this->noSqlConsoleUpdateUsers($login, $password, $userGroup),
      'redis' => $this->noSqlConsoleUpdateUsers($login, $password, $userGroup),

      default => $this->sqlConsoleUpdateUsers($login, $password, $userGroup)
    };
  }
}

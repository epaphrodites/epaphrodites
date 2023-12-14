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

    return $this->checkDbType() === true ? $this->sqlUpdateUserDatas($nomprenoms, $email, $number) : $this->noSqlUpdateUserDatas($nomprenoms, $email, $number);
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return bool
   */
  public function updateEtatsUsers(string $login): bool
  {

    return $this->checkDbType() === true ? $this->sqlUpdateEtatsUsers($login) : $this->noSqlUpdateEtatsUsers($login);
  }

  /**
   * Verify if exist in database
   *
   * @param string $loginuser
   * @return bool
   */
  public function initUsersPassword(string $login): bool
  {

    return $this->checkDbType() === true ? $this->sqlInitUsersPassword($login) : $this->noSqlInitUsersPassword($login);
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

    return $this->checkDbType() === true ? $this->sqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp) : $this->noSqlChangeUsersPassword($oldPassword, $newPassword, $confirMdp);
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

    return $this->checkDbType() === true ? $this->sqlConsoleUpdateUsers($login, $password, $userGroup) : $this->noSqlConsoleUpdateUsers($login, $password, $userGroup);
  }
}

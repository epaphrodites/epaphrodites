<?php

namespace Epaphrodites\epaphrodites\auth;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class StartUsersSession extends epaphroditeClass
{

  private $locate;

  public function StartUsersSession($authId, $authLogin, $authNameSurname, $authContact, $authEmail, $authUsersGroup)
  {

    session_status() === PHP_SESSION_ACTIVE ?: session_start();

    static::class('global')->StartSession($authId, $authLogin, $authNameSurname, $authContact, $authEmail, $authUsersGroup);

    session_regenerate_id();

    if (static::class('secure')->get_csrf($this->key()) !== 0) {

      static::class('cookies')->set_user_cookies($this->key());
    }

    $this->locate = static::class('paths')->dashboard();

    header("Location: $this->locate ");
  }

/**
 * Current cookies value
 */
private function key(): string
{
    return !empty(static::class('secure')->CheckUserCrsfToken()) ? static::class('secure')->CheckUserCrsfToken() : $_COOKIE[static::class('msg')->answers('token_name')];
}}
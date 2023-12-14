<?php

namespace Epaphrodite\epaphrodite\auth\Contracts;

interface StartSession
{

    public function StartUsersSession($AuthId , $AuthLogin , $AuthNomprenoms , $AuthContact , $AuthEmail , $AuthTypes);

}
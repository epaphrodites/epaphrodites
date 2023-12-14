<?php

namespace Epaphrodite\epaphrodite\auth;

use Epaphrodite\epaphrodite\env\config\GeneralConfig;

class HardSession extends GeneralConfig{

    /**
     * @param mixed $id
     * @param mixed $login
     * @param mixed $email
     * @param mixed $contact
     * @param mixed $usersGroup
     * @param mixed $nameSurname
     * @return void
    */
    public function StartSession( $id , $login , $nameSurname , $contact , $email , $usersGroup )
    {

        // Set id session value
        $this->SetSession( _AUTH_ID_ , $id);

        // Set login session value
        $this->SetSession( _AUTH_LOGIN_ , $login);

        // Set others login session value
        $this->SetSession( _AUTH_OTHER_LOGIN_ , 'D'.$login);        

        // Set name and surname session value
        $this->SetSession( _AUTH_NAME_ , $nameSurname);

        // Set contact session value 
        $this->SetSession( _AUTH_CONTACT_ , $contact);

        // Set email session value
        $this->SetSession( _AUTH_EMAIL_ , $email);

        // Set usersGroup user session value
        $this->SetSession( _AUTH_TYPE_ , $usersGroup );
    }

}
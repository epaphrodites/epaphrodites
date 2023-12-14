<?php

namespace Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\Lists;

use Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\RighstList;

class GetModulesList extends RighstList
{

    /**
     * @return array
     */
    public static function GetModuleList():array
    {

        return 
        [
            'profil' => 'MY PROFILE',
            'search' => 'SEARCH',
            'print' => 'PRINT MANAGEMENT',  
            'import' => 'IMPORT MANAGEMENT',
            'export' => 'EXPORT MANAGEMENT',
            'statistic' => 'STATICS MANAGEMENT',
            'annuaire' => 'DIRECTORY MANAGEMENT',
            'habilit' => 'AUTHORIZATIONS',
            'faq' => 'FAQ (Frequently Asked Questions)',
            'chats' => 'MESSAENGER SERVICE',
            'users' => 'USERS MANAGEMENT',
            'actions' => 'ACTIONS MANAGEMENT',
            'setting' => 'SYSTEM SETTING',
        ];
     } 
 }
<?php

namespace Epaphrodites\epaphrodites\EpaphMozart\ModulesConfig\Lists;

use Epaphrodites\epaphrodites\constant\epaphroditeClass;

class GetRightList extends epaphroditeClass
{

   /**
    * @return array
    */
   public static function RightList():array
   {

      return [
         [
            'apps' => 'profil',
            'libelle' => "Change password",
            'path' => 'users/change_password'
         ],
         [
            'apps' => 'profil',
            'libelle' => "Change my informations",
            'path' => 'users/edit_users_infos'
         ],
         [
            'apps' => 'chatbot',
            'libelle' => 'Start chatbot model one',
            'path' => 'chats/start_chatbot_model_one',
         ],   
         [
            'apps' => 'chatbot',
            'libelle' => 'Start chatbot model two',
            'path' => 'chats/start_chatbot_model_two',
         ], 
         [
            'apps' => 'chatbot',
            'libelle' => 'Start chatbot model three',
            'path' => 'chats/start_chatbot_model_three',
         ],                                              
         [
            'apps' => 'users',
            'libelle' => 'Import system users',
            'path' => 'users/import_users',
         ], 
         [
            'apps' => 'users',
            'libelle' => 'List of system all Users',
            'path' => 'users/all_users_list',
         ], 
         [
            'apps' => 'actions',
            'libelle' => 'List of recent actions',
            'path' => 'setting/list_of_recent_actions',
         ],           

        ];
        
    }
 }
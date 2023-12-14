<?php

namespace Epaphrodite\epaphrodite\EpaphMozart\ModulesConfig\Lists;

use Epaphrodite\epaphrodite\constant\epaphroditeClass;

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
            'apps' => 'chats',
            'libelle' => 'Chat messages',
            'path' => 'chats/List_of_messages',
         ],           
         [
            'apps' => 'users',
            'libelle' => 'Import Users',
            'path' => 'users/import_users',
         ], 
         [
            'apps' => 'users',
            'libelle' => 'List of all Users',
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
<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class users extends MainSwitchers
{

    private object $msg;
    private object $session;
    private object $getId;    
    private object $count;
    private object $update;
    private object $select;
    private object $insert;
    private string $ans = '';
    private string $alert = '';
    private array|bool $result = [];
    private object $importFiles;

    /**
     * Initialize object properties when an instance is created
     * @return  object
     */
    public final function __construct()
    {
        $this->initializeObjects();
    }

    /**
     * Initialize each property using values retrieved from static configurations
     * 
     * @return void
     */
    private function initializeObjects(): void
    {
        $this->msg = $this->getFunctionObject(static::initNamespace(), 'msg');
        $this->getId = $this->getFunctionObject(static::initQuery(), 'getid');
        $this->count = $this->getFunctionObject(static::initQuery(), 'count');
        $this->select = $this->getFunctionObject(static::initQuery(), 'select');
        $this->insert = $this->getFunctionObject(static::initQuery(), 'insert');
        $this->update = $this->getFunctionObject(static::initQuery(), 'update');
        $this->session = $this->getFunctionObject(static::initNamespace(), 'session');
        $this->importFiles = $this->getFunctionObject(static::initConfig(), 'import');
    }         

    /**
     * Update user datas
     * 
     * @param string $html
     * @return void
     */
    public final function editUsersInfos(string $html): void
    {

        $login = $this->session->login();

        if (static::isValidMethod()) {

            $this->result = $this->update->updateUserDatas(static::getPost('__username__'), static::getPost('__email__'), static::getPost('__contact__'));
            if ($this->result === true) {
                $this->ans = $this->msg->answers('succes');
                $this->alert = 'alert-success';
            }
            if ($this->result === false) {
                $this->ans = $this->msg->answers('erreur');
                $this->alert = 'alert-danger';
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'alert' => $this->alert,
                'reponse' => $this->ans,
                'select' => $this->getId->GetUsersDatas($login),
            ],
            true
        )->get();
    }

    /**
     * Update user password
     * 
     * @param string $html
     * @return void
     */
    public final function changePassword(string $html): void
    {

        if (static::isValidMethod()) {

            $this->result = $this->update->changeUsersPassword(static::getPost('__oldpassword__'), static::getPost('__newpassword__'), static::getPost('__confirm__'));

            if ($this->result === 1) {
                $this->ans = $this->msg->answers('no-identic');
                $this->alert = 'alert-danger';
            }
            if ($this->result === 2) {
                $this->ans = $this->msg->answers('no-identic');
                $this->alert = 'alert-danger';
            }
            if ($this->result === 3) {
                $this->ans = $this->msg->answers('mdpnotsame');
                $this->alert = 'alert-danger';
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'reponse' => $this->ans,
                'alert' => $this->alert,
            ],
            true
        )->get();
    }

    /**
     * upload users datas
     * 
     * @param string $html
     * @return void
     */
    public final function importUsers(string $html): void
    {

        if (static::isValidMethod()) {

            $SheetData = $this->importFiles->ImportExcelFiles($_FILES['file']['name']);

            if (!empty($SheetData)) {
                
                for ($i = 1; $i < count($SheetData); $i++) {

                    $CodeUtilisateur = $SheetData[$i][0];

                    $this->result = $this->insert->addUsers($CodeUtilisateur, static::getPost('__group__'));

                    if ($this->result === true) {
                        $this->ans = $this->msg->answers('succes');
                        $this->alert = 'alert-success';
                    }
                    if ($this->result === false) {
                        $this->ans = $this->msg->answers('erreur');
                        $this->alert = 'alert-danger';
                    }
                }
            } else {
                $this->ans = $this->msg->answers('fileempty');
                $this->alert = 'alert-danger';
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'reponse' => $this->ans,
                'alert' => $this->alert,
            ],
            true
        )->get();
    }

    /**
     * Users 
     * 
     * @param string $html
     * @return void
     */
    public final function allUsersList(string $html): void
    {

        $total = 0;
        $list = [];
        $Nbreligne = 100;
        $page = static::isGet('_p') ? static::getGet('_p') : 1;
        $position = static::notEmpty(['filtre'] , 'GET') ? static::getGet('filtre') : NULL;

        if (static::isPost('_sendselected_') && static::notEmpty(['users' , '_sendselected_'])) {

            foreach (static::isArray('users') as $login) {

                $this->result = static::isSelected('_sendselected_', 1 ) 
                    ? $this->update->updateEtatsUsers($login) : 
                    $this->update->initUsersPassword($login);
            }

            if ($this->result === true) {
                $this->ans = $this->msg->answers('succes');
                $this->alert = 'alert-success';
            }
            if ($this->result === false) {
                $this->ans = $this->msg->answers('error');
                $this->alert = 'alert-danger';
            }
        }

        if (static::isGet('submitsearch') && static::notEmpty(['datasearch'] , 'GET')) {

            $list = $this->getId->GetUsersDatas($_GET['datasearch']);
            $total = count($list ?? []);
            
        }else {

            $total = static::notEmpty(['filtre'] , 'GET') ? $this->count->CountUsersByGroup($_GET['filtre']) : $this->count->CountAllUsers();
            $list = static::notEmpty(['filtre'] , 'GET') ? $this->getId->GetUsersByGroup($page, $Nbreligne, $_GET['filtre']) : $this->select->listeOfAllUsers($page, $Nbreligne);
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'total' => $total,
                'current' => $page,
                'liste_users' => $list,
                'alert' => $this->alert,
                'reponse' => $this->ans,
                'position' => $position,
                'select' => $this->getId,
                'nbrePage' => ceil(($total) / $Nbreligne),
            ],
            true
        )->get();
    }
}

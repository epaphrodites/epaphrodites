<?php

namespace Epaphrodite\controllers\controllers;

use Epaphrodite\controllers\switchers\MainSwitchers;
use Epaphrodite\epaphrodite\ExcelFiles\ImportFiles\ImportFiles;

final class users extends MainSwitchers
{
    private string $ans = '';
    private string $alert = '';
    private array|bool $result = [];
    private object $importFiles;

    public function __construct()
    {
        $this->importFiles = new ImportFiles;
    }

    /**
     * Update user datas
     * @param string $html
     * @return mixed
     */
    public function editUsersInfos(string $html): void
    {

        $login = static::initNamespace()['session']->login();

        if (static::isPost('submit')) {

            $this->result = static::initQuery()['update']->updateUserDatas(static::getPost('__username__'), static::getPost('__email__'), static::getPost('__contact__'));
            if ($this->result === true) {
                $this->ans = static::initNamespace()['msg']->answers('succes');
                $this->alert = 'alert-success';
            }
            if ($this->result === false) {
                $this->ans = static::initNamespace()['msg']->answers('erreur');
                $this->alert = 'alert-danger';
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'alert' => $this->alert,
                'reponse' => $this->ans,
                'select' => static::initQuery()['getid']->GetUsersDatas($login),
            ],
            true
        )->get();
    }

    /**
     * Update user password
     * @param string $html
     * @return mixed
     */
    public function changePassword(string $html): void
    {

        if (static::isPost('submit')) {

            $this->result = static::initQuery()['update']->changeUsersPassword(static::getPost('__oldpassword__'), static::getPost('__newpassword__'), static::getPost('__confirm__'));

            if ($this->result === 1) {
                $this->ans = static::initNamespace()['msg']->answers('no-identic');
                $this->alert = 'alert-danger';
            }
            if ($this->result === 2) {
                $this->ans = static::initNamespace()['msg']->answers('no-identic');
                $this->alert = 'alert-danger';
            }
            if ($this->result === 3) {
                $this->ans = static::initNamespace()['msg']->answers('mdpnotsame');
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
     * @return mixed
     */
    public function importUsers(string $html): void
    {

        if (static::isPost('submit')) {

            $SheetData = $this->importFiles->ImportExcelFiles($_FILES['file']['name']);

            if (!empty($SheetData)) {
                
                for ($i = 1; $i < count($SheetData); $i++) {

                    $CodeUtilisateur = $SheetData[$i][0];

                    $this->result = static::initQuery()['insert']->addUsers($CodeUtilisateur, static::getPost('__group__'));

                    if ($this->result === true) {
                        $this->ans = static::initNamespace()['msg']->answers('succes');
                        $this->alert = 'alert-success';
                    }
                    if ($this->result === false) {
                        $this->ans = static::initNamespace()['msg']->answers('erreur');
                        $this->alert = 'alert-danger';
                    }
                }
            } else {
                $this->ans = static::initNamespace()['msg']->answers('fileempty');
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
     * Users list
     * @param string $html
     * @return void
     */
    public function allUsersList(string $html): void
    {

        $total = 0;
        $list = [];
        $Nbreligne = 100;
        $page = static::isGet('_p') ? static::getGet('_p') : 1;
        $position = static::notEmpty(['filtre'] , 'GET') ? static::getGet('filtre') : NULL;

        if (static::isPost('_sendselected_') && static::notEmpty(['users' , '_sendselected_'])) {

            foreach (static::isArray('users') as $login) {

                $this->result = static::isSelected('_sendselected_', 1 ) 
                    ? static::initQuery()['update']->updateEtatsUsers($login) : 
                    static::initQuery()['update']->initUsersPassword($login);
            }

            if ($this->result === true) {
                $this->ans = static::initNamespace()['msg']->answers('succes');
                $this->alert = 'alert-success';
            }
            if ($this->result === false) {
                $this->ans = static::initNamespace()['msg']->answers('error');
                $this->alert = 'alert-danger';
            }
        }

        if (static::isGet('submitsearch') && static::notEmpty(['datasearch'] , 'GET')) {

            $list = static::initQuery()['getid']->GetUsersDatas($_GET['datasearch']);
            $total = count($list ?? []);
            
        }else {

            $total = static::notEmpty(['filtre'] , 'GET') ? static::initQuery()['count']->CountUsersByGroup($_GET['filtre']) : static::initQuery()['count']->CountAllUsers();
            $list = static::notEmpty(['filtre'] , 'GET') ? static::initQuery()['getid']->GetUsersByGroup($page, $Nbreligne, $_GET['filtre']) : static::initQuery()['select']->listeOfAllUsers($page, $Nbreligne);
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'total' => $total,
                'current' => $page,
                'liste_users' => $list,
                'alert' => $this->alert,
                'reponse' => $this->ans,
                'position' => $position,
                'select' => static::initQuery()['getid'],
                'nbrePage' => ceil(($total) / $Nbreligne),
            ],
            true
        )->get();
    }
}

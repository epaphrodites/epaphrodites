<?php

namespace Epaphrodite\controllers\controllers;

use Epaphrodite\controllers\switchers\MainSwitchers;

final class setting extends MainSwitchers
{

    private string $alert = '';
    private string $ans = '';
    private array|bool $result = [];

    /**
     * Adds user access rights.
     *
     * @param string $html
     * @return mixed
     */
    public function assignUserAccessRights(string $html): void
    {

        $idtype = static::isGet('_see') ? static::getGet('_see') : 0;

        if (static::isPost('submit') && $idtype !== 0) {

            $this->result = static::initQuery()['insert']->AddUsersRights($idtype, static::getPost('__rights__'), static::getPost('__actions__'));

            if ($this->result === true) {
                $this->alert = 'alert-success';
                $this->ans = static::initNamespace()['msg']->answers('succes');
            }

            if ($this->result === false) {
                $this->alert = 'alert-danger';
                $this->ans = static::initNamespace()['msg']->answers('rightexist');
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'type' => $idtype,
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'env' => static::initNamespace()['env'],
                'datas' => static::initNamespace()['datas'],
                'select' => static::initNamespace()['mozart']
            ],
            true
        )->get();
    }

    /**
     * Lists all user groups with their rights.
     *
     * @param string $html
     * @return mixed
     */
    public function listOfUserRightsManagement(string $html): void
    {

        $idtype = static::isGet('_see') ? static::getGet('_see') : 0;

        if (static::isPost('_sendselected_') && static::notEmpty(['group' , '_sendselected_'])) {

            // Authorize user right
            if (static::isSelected('_sendselected_', 1 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = static::initQuery()['update']->updateUserRights($UsersGroup, 1);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = static::initNamespace()['msg']->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = static::initNamespace()['msg']->answers('error');
                }
            }

            // Refuse user right
            if (static::isSelected('_sendselected_', 2 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = static::initQuery()['update']->updateUserRights($UsersGroup, 0);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = static::initNamespace()['msg']->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = static::initNamespace()['msg']->answers('error');
                }
            }

            // Deleted user right
            if (static::isSelected('_sendselected_', 3 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = static::initQuery()['delete']->DeletedUsersRights($UsersGroup);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = static::initNamespace()['msg']->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = static::initNamespace()['msg']->answers('error');
                }
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'list' => static::initNamespace()['mozart'],
                'select' => static::initQuery()['getid']->getUsersRights($idtype),
            ],
            true
        )->get();
    }

    /**
     * Manages user access rights per group.
     *
     * @param string $html
     * @return mixed
     */
    public function managementOfUserAccessRights(string $html): void
    {

        if (static::isPost('__deleted__')) {

            $this->result = static::initQuery()['delete']->EmptyAllUsersRights(static::getPost('__deleted__'));

            if ($this->result === true) {
                $this->alert = 'alert-success';
                $this->ans = static::initNamespace()['msg']->answers('succes');
            }
            if ($this->result === false) {
                $this->alert = 'alert-danger';
                $this->ans = static::initNamespace()['msg']->answers('error');
            }
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'select' => static::initNamespace()['datas']->userGroup(),
                'auth' => static::class('session'),
                'reponse' => $this->ans,
                'alert' => $this->alert
            ],
            true
        )->get();
    }

    /**
     * List of recent actions
     * @param string $html
     * @return void
     */
    public function listOfRecentActions(string $html): void
    {

        $total = 0;
        $list = [];
        $Nbreligne = 100;
        $page = static::isGet('_p') ? static::getGet('_p') : 1;
        $position = static::notEmpty(['filtre'] , 'GET') ? static::getGet('filtre') : NULL;

        if (static::isGet('submitsearch') && static::notEmpty(['datasearch'] , 'GET')) {

            $list = static::initQuery()['getid']->getUsersRecentsActions($_GET['datasearch']);
            $total = count($list ?? []);
        } else {

            $total = static::initQuery()['count']->countUsersRecentActions();
            $list = static::initQuery()['select']->listOfRecentActions($page, $Nbreligne);
        }

        static::rooter()->target(_DIR_ADMIN_TEMP_ . $html)->content(
            [
                'current' => $page,
                'total' => $total,
                'liste_users' => $list,
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'position' => $position,
                'nbrePage' => ceil(($total) / $Nbreligne),
                'select' => static::initQuery()['getid'],
            ],
            true
        )->get();
    }    
}

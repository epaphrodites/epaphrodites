<?php

namespace Epaphrodites\controllers\controllers;

use Epaphrodites\controllers\switchers\MainSwitchers;

final class setting extends MainSwitchers
{
    
    private object $msg;
    private object $env;
    private object $datas;
    private object $count;
    private object $getId;
    private object $mozart;
    private object $update;
    private object $select;
    private object $insert;
    private object $delete;
    private string $alert = '';
    private string $ans = '';
    private array|bool $result = [];

    /**
     * Initialize object properties when an instance is created
     * 
     * @return void
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
        $this->env = $this->getFunctionObject(static::initNamespace(), 'env');
        $this->getId = $this->getFunctionObject(static::initQuery(), 'getid');
        $this->count = $this->getFunctionObject(static::initQuery(), 'count');
        $this->select = $this->getFunctionObject(static::initQuery(), 'select');
        $this->insert = $this->getFunctionObject(static::initQuery(), 'insert');
        $this->delete = $this->getFunctionObject(static::initQuery(), 'delete');
        $this->update = $this->getFunctionObject(static::initQuery(), 'update');
        $this->datas = $this->getFunctionObject(static::initNamespace(), 'datas');
        $this->mozart = $this->getFunctionObject(static::initNamespace(), 'mozart');
    }      

    /**
     * Adds user access rights.
     *
     * @param string $html
     * @return void
     */
    public final function assignUserAccessRights(string $html): void
    {

        $idtype = static::isGet('_see') ? static::getGet('_see') : 0;

        if (static::isValidMethod(true) && $idtype !== 0) {

            $this->result = $this->insert->AddUsersRights($idtype, static::getPost('__rights__'), static::getPost('__actions__'));

            if ($this->result === true) {
                $this->alert = 'alert-success';
                $this->ans = $this->msg->answers('succes');
            }

            if ($this->result === false) {
                $this->alert = 'alert-danger';
                $this->ans = $this->msg->answers('rightexist');
            }
        }

        $this->views( $html, 
            [
                'type' => $idtype,
                'env' => $this->env,
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'datas' => $this->datas,
                'select' => $this->mozart
            ],
            true
        );
    }

    /**
     * Lists all user groups with their rights.
     *
     * @param string $html
     * @return void
     */
    public final function listOfUserRightsManagement(string $html): void
    {

        $idtype = static::isGet('_see') ? static::getGet('_see') : 0;

        if (static::isPost('_sendselected_') && static::notEmpty(['group' , '_sendselected_'])) {

            // Authorize user right
            if (static::isSelected('_sendselected_', 1 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = $this->update->updateUserRights($UsersGroup, 1);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = $this->msg->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = $this->msg->answers('error');
                }
            }

            // Refuse user right
            if (static::isSelected('_sendselected_', 2 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = $this->update->updateUserRights($UsersGroup, 0);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = $this->msg->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = $this->msg->answers('error');
                }
            }

            // Deleted user right
            if (static::isSelected('_sendselected_', 3 )) {

                foreach (static::isArray('group') as $UsersGroup) {

                    $this->result = $this->delete->DeletedUsersRights($UsersGroup);
                }

                if ($this->result === true) {
                    $this->alert = 'alert-success';
                    $this->ans = $this->msg->answers('succes');
                }
                if ($this->result === false) {
                    $this->alert = 'alert-danger';
                    $this->ans = $this->msg->answers('error');
                }
            }
        }

        $this->views( $html, 
            [
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'list' => $this->mozart,
                'select' => $this->getId->getUsersRights($idtype),
            ],
            true
        );
    }

    /**
     * Manages user access rights per group.
     *
     * @param string $html
     * @return void
     */
    public final function managementOfUserAccessRights(string $html): void
    {

        if (static::isPost('__deleted__')) {

            $this->result = $this->delete->EmptyAllUsersRights(static::getPost('__deleted__'));

            if ($this->result === true) {
                $this->alert = 'alert-success';
                $this->ans = $this->msg->answers('succes');
            }
            if ($this->result === false) {
                $this->alert = 'alert-danger';
                $this->ans = $this->msg->answers('error');
            }
        }

        $this->views( $html, 
            [
                'select' => $this->datas->userGroup(),
                'auth' => static::class('session'),
                'reponse' => $this->ans,
                'alert' => $this->alert
            ],
            true
        );
    }

    /**
     * List of recent actions
     * @param string $html
     * @return void
     */
    public final function listOfRecentActions(string $html): void
    {

        $total = 0;
        $list = [];
        $Nbreligne = 100;
        $page = static::isGet('_p') ? static::getGet('_p') : 1;
        $position = static::notEmpty(['filtre'] , 'GET') ? static::getGet('filtre') : NULL;

        if (static::isGet('submitsearch') && static::notEmpty(['datasearch'] , 'GET')) {

            $list = $this->getId->getUsersRecentsActions($_GET['datasearch']);
            $total = count($list ?? []);
        } else {

            $total = $this->count->countUsersRecentActions();
            $list = $this->select->listOfRecentActions($page, $Nbreligne);
        }

        $this->views( $html, 
            [
                'current' => $page,
                'total' => $total,
                'liste_users' => $list,
                'reponse' => $this->ans,
                'alert' => $this->alert,
                'position' => $position,
                'nbrePage' => ceil(($total) / $Nbreligne),
                'select' => $this->getId,
            ],
            true
        );
    }     
}

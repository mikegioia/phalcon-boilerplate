<?php

namespace Controllers;

class LoginController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->checkLoggedIn = FALSE;

        parent::beforeExecuteRoute();
    }

    public function indexAction()
    {
        $this->view->pick( 'main/login' );
    }
}
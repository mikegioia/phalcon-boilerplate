<?php

namespace Controllers;

class DashboardController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        return parent::beforeExecuteRoute();
    }

    public function indexAction()
    {
        $this->view->user = $this->auth->getUser();
    }
}
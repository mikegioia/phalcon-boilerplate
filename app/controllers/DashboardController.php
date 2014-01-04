<?php

namespace Controllers;

class DashboardController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
    }

    public function indexAction()
    {
        $this->view->user = \Lib\Auth::getUser();
    }
}
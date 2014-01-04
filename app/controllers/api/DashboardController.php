<?php

namespace Controllers\Api;

class DashboardController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
    }

    public function indexAction()
    {
        $this->data->user = \Lib\Auth::getUser();
    }
}
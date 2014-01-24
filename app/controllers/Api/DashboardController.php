<?php

namespace Controllers\Api;

class DashboardController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->responseMode = 'api';

        parent::beforeExecuteRoute();
    }

    public function indexAction()
    {
        $this->data->user = $this->auth->getUser();
    }
}
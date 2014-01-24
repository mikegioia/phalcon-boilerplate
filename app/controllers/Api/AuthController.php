<?php

namespace Controllers\Api;

class AuthController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->checkLoggedIn = FALSE;
        $this->responseMode = 'api';

        parent::beforeExecuteRoute();
    }

    /**
     * Accepts an email and password, attempts to log user in
     * and create a session. Returns a session ID.
     */
    public function loginAction()
    {
        $email = $this->request->getPost( 'email' );
        $password = $this->request->getPost( 'password' );

        // try to log in with credentials
        //
        $action = new \Actions\Users\Auth();
        $loggedIn = $action->login(
            array(
                'email' => $email,
                'password' => $password
            ));

        if ( ! $loggedIn )
        {
            $this->setStatus( ERROR );
            return FALSE;
        }

        $this->data->user_id = $this->auth->getUserId();
        $this->data->session_id = $this->session->getId();
    }

    /**
     * Kill the session and login cookie
     */
    public function logoutAction()
    {
        $action = new \Actions\Users\Auth();
        $action->destroyToken();
        $action->destroySession();

        $this->cookies->get( 'token' )->delete();
        $this->addMessage( "You've been logged out" ); 
    }
}
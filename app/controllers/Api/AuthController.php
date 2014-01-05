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
        $loggedIn = \Actions\Users\Auth::login(
            array(
                'email' => $email,
                'password' => $password
            ));

        if ( ! $loggedIn )
        {
            $this->setStatus( ERROR );
            return FALSE;
        }

        $this->data->user_id = \Lib\Auth::getUserId();
        $this->data->session_id = $this->session->getId();
    }

    /**
     * Kill the session and login cookie
     */
    public function logoutAction()
    {
        \Actions\Users\Auth::destroyToken();
        \Actions\Users\Auth::destroySession();

        $this->cookies->get( 'token' )->delete();
        $this->addMessage( "You've been logged out" ); 
    }
}
<?php

namespace Controllers;

class AuthController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->checkLoggedIn = FALSE;

        return parent::beforeExecuteRoute();
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
        $this->redirect = 'dashboard';
        $action = new \Actions\Users\Auth();
        $loggedIn = $action->login(
            array(
                'email' => $email,
                'password' => $password
            ));

        if ( ! $loggedIn )
        {
            $this->redirect = 'login';
        }
    }

    /**
     * Kill the session and login cookie
     */
    public function logoutAction()
    {
        $action = new \Actions\Users\Auth();
        $action->destroyToken();
        $action->destroySession( FALSE );

        $this->cookies->get( 'token' )->delete();

        $this->redirect = 'login';
        $this->addMessage( "You've been logged out" );
    }
}

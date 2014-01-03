<?php

namespace Controllers;

class AuthController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->checkLoggedIn = FALSE;

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

        // @TODO redirect to either dashboard or login page
    }

    /**
     * Kill the session and login cookie
     */
    public function logoutAction()
    {
        \Actions\Users\Auth::destroyToken();
        \Actions\Users\Auth::destroySession();

        $this->cookies->get( 'token' )->delete(); // doesn't work

        $this->addMessage( "You've been logged out" ); 

        // @TODO redirect to login page
    }
}

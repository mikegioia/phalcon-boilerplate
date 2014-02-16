<?php

namespace Lib\Services;

use Db\Sql\Users;

/**
 * Manages identity authentication and permissions in Phoenix
 *
* @depends service session
 */
class Auth extends \Base\Service
{
    public $userId;
    public $user;
    public $userObj; // full user object from database

    /**
     * Sets up the session data, builds the user info
     */
    public function init()
    {
        // save the userId from the session
        //
        $this->userId = $this->getDI()
            ->getShared( 'session' )
            ->get( 'user_id' );

        // read in the session. if it's not set, try to re-authorize them
        // with a login token. authorizeToken will set the session info
        // if a valid token exists.
        //
        if ( ! $this->isLoggedIn() )
        {
            $action = new \Actions\Users\Auth();

            if ( ! $action->authorizeToken() )
            {
                return FALSE;
            }

            // update the auth from the session
            //
            $this->userId = $this->getDI()
                ->getShared( 'session' )
                ->get( 'user_id' );
        }

        // load user, roles, and settings. if the requested userId is the
        // same as the session, then just use the session user.
        //
        $this->load( $this->userId );
    }

    public function destroy()
    {
        $this->user = NULL;
        $this->userId = NULL;
        $this->userObj = NULL;
    }

    public function isLoggedIn()
    {
        return valid( $this->getUserId() );
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserObj()
    {
        return $this->userObj;
    }

    /**
     * Load a the user, roles, and settings internally. If no user ID
     * is specified user the one from the session.
     *
     * @param integer $userId
     */
    public function load( $userId )
    {
        if ( ! valid( $userId ) )
        {
            throw new \Base\Exception(
                'No user ID specified or exists in the session' );
        }

        $this->loadUser( $userId );
    }

    /**
     * Load a user internally.
     *
     * @param integer $userId
     */
    private function loadUser( $userId )
    {
        $this->userObj = \Db\Sql\Users::findFirst( $userId );
        $this->user = $this->userObj->toArray();
        $this->userId = $this->userObj->id;
    }
}
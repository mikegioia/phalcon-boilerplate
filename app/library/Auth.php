<?php

namespace Lib;

use Db\Sql\Users;

/**
 * Manages identity authentication and permissions in Phoenix
 */
class Auth extends \Base\Library
{
    public static $userId;
    public static $user;
    public static $userObj; // full user object from database

    /**
     * Sets up the session data, builds the user info
     */
    public static function init()
    {
        // save the userId from the session
        //
        self::$userId = self::getService( 'session' )->get( 'user_id' );

        // read in the session. if it's not set, try to re-authorize them
        // with a login token. authorizeToken will set the session info
        // if a valid token exists.
        //
        if ( ! self::isLoggedIn() )
        {
            if ( ! \Actions\Users\Auth::authorizeToken() )
            {
                return FALSE;
            }
        }

        // load user, roles, and settings. if the requested userId is the
        // same as the session, then just use the session user.
        //
        self::load( self::$userId );
    }

    public static function destroy()
    {
        self::$user = NULL;
        self::$userId = NULL;
        self::$userObj = NULL;
    }

    public static function isLoggedIn()
    {
        return valid( self::getUserId() );
    }

    public static function getUserId()
    {
        return self::$userId;
    }

    public static function getUser()
    {
        return self::$user;
    }

    public static function getUserObj()
    {
        return self::$userObj;
    }

    /**
     * Load a the user, roles, and settings internally. If no user ID
     * is specified user the one from the session.
     *
     * @param integer $userId
     */
    public static function load( $userId )
    {
        if ( ! valid( $userId ) )
        {
            throw new \Base\Exception(
                'No user ID specified or exists in the session' );
        }

        self::loadUser( $userId );
    }

    /**
     * Load a user internally.
     *
     * @param integer $userId
     */
    private static function loadUser( $userId )
    {
        self::$userObj = \Db\Sql\Users::findFirst( $userId );
        self::$user = self::$userObj->toArray();
        self::$userId = self::$userObj->id;
    }
}
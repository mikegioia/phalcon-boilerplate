<?php

namespace Actions\Users;

use \Phalcon\DI as DI;

class AuthTest extends \UnitTestCase
{
    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testPasswordHash()
    {
        $security = $this->di->get( 'security' );
        $cryptedPassword = $security->hash( 'password' );

        $this->assertTrue(
            $security->checkHash(
                'password',
                $cryptedPassword
            ));
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testSession()
    {
        $session = $this->di->get( 'session' );

        $this->assertFalse( $session->start() );
        $this->assertTrue( $session->isStarted() );

        $session->set( 'some', 'value' );
        $this->assertEquals(
            $session->get( 'some' ),
            'value' );
        $this->assertTrue( $session->has( 'some' ) );
        $this->assertEquals(
            $session->get( 'undefined', 'my-default' ),
            'my-default' );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testRandomToken()
    {
        $action = new \Actions\Users\Auth();
        $token1 = $action->generateRandomToken();
        $this->assertTrue( strlen( $token1 ) == 40 );

        $token2 = $action->generateRandomToken();
        $this->assertFalse( $token1 == $token2 );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCreateDestroyToken()
    {
        $action = new \Actions\Users\Auth();
        $token = $action->createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        $this->assertTrue( $action->destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testLoginWithToken()
    {
        $action = new \Actions\Users\Auth();
        $token = $action->createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        
        $user = $action->authorizeToken();
        $this->assertTrue( $user != FALSE );
        $this->assertObjectHasAttribute( 'id', $user );
        $this->assertTrue( valid( $user->id ) );
        $this->assertTrue( $action->destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNoLoginParams()
    {
        $params = array();
        $action = new \Actions\Users\Auth();
        $util = $this->di->get( 'util' );

        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 3, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginParams()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'not an email',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNonExistingLoginEmail()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'missing@example.org',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginCredentials()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'incorrect' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertFalse( $action->login( $params ) );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCorrectLoginCredentials()
    {
        $util = $this->di->get( 'util' );
        $action = new \Actions\Users\Auth();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'password' );

        $this->assertCount( 0, $util->getMessages() );
        $this->assertTrue( $action->login( $params ) );
        $this->assertCount( 0, $util->getMessages() );

        $session = $this->di->get( 'session' );
        $this->assertTrue( valid( $session->get( 'user_id' ) ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testDestroyCookieSession()
    {
        $action = new \Actions\Users\Auth();
        $this->assertTrue( $action->destroyToken( 1 ) );
        $this->assertTrue( $action->destroySession() );

        $session = $this->di->get( 'session' );
        $this->assertFalse( valid( $session->get( 'user_id' ) ) );
    }
}

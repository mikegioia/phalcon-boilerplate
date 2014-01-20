<?php

namespace Actions\Users;

use \Phalcon\DI as DI;

class AuthTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testPasswordHash()
    {
        $security = DI::getDefault()->getSecurity();
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
        $session = DI::getDefault()->getSession();

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
        $token1 = \Actions\Users\Auth::generateRandomToken();
        $this->assertTrue( strlen( $token1 ) == 40 );

        $token2 = \Actions\Users\Auth::generateRandomToken();
        $this->assertFalse( $token1 == $token2 );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCreateDestroyToken()
    {
        $token = \Actions\Users\Auth::createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testLoginWithToken()
    {
        $token = \Actions\Users\Auth::createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        
        $user = \Actions\Users\Auth::authorizeToken();
        $this->assertTrue( $user != FALSE );
        $this->assertObjectHasAttribute( 'id', $user );
        $this->assertTrue( valid( $user->id ) );
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 1 ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNoLoginParams()
    {
        $params = array();
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 3, \Lib\Util::getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginParams()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'not an email',
            'password' => 'password' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testNonExistingLoginEmail()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'missing@example.org',
            'password' => 'password' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testBadLoginCredentials()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'incorrect' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testCorrectLoginCredentials()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'test@example.org',
            'password' => 'password' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertTrue(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 0, \Lib\Util::getMessages() );

        $session = DI::getDefault()->getSession();
        $this->assertTrue( valid( $session->get( 'user_id' ) ) );
    }

    /**
     * @group actions
     * @group users
     * @group auth
     */
    public function testDestroyCookieSession()
    {
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 1 ) );
        $this->assertTrue(
            \Actions\Users\Auth::destroySession() );

        $session = DI::getDefault()->getSession();
        $this->assertFalse( valid( $session->get( 'user_id' ) ) );
    }
}

<?php

namespace Actions\Users;

use \Phalcon\DI as DI;

class AuthTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSession()
    {
        $session = DI::getDefault()->getSession();

        $this->assertFalse( $session->start() );
        $this->assertFalse( $session->isStarted() );

        @session_start();

        $session->set( 'some', 'value' );
        $this->assertEquals(
            $session->get( 'some' ),
            'value' );
        $this->assertTrue( $session->has( 'some' ) );
        $this->assertEquals(
            $session->get( 'undefined', 'my-default' ),
            'my-default' );
    }

    public function testRandomToken()
    {
        $token1 = \Actions\Users\Auth::generateRandomToken();
        $this->assertTrue( strlen( $token1 ) == 40 );

        $token2 = \Actions\Users\Auth::generateRandomToken();
        $this->assertFalse( $token1 == $token2 );
    }

    public function testCreateDestroyToken()
    {
        $token = \Actions\Users\Auth::createToken( 1, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 1 ) );
    }

    public function testLoginWithToken()
    {
        $token = \Actions\Users\Auth::createToken( 2, TRUE );
        $this->assertTrue( strlen( $token ) > 0 );
        
        $user = \Actions\Users\Auth::authorizeToken();
        $this->assertTrue( $user != FALSE );
        $this->assertObjectHasAttribute( 'id', $user );
        $this->assertTrue( valid( $user->id ) );
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 2 ) );
    }

    public function testNoLoginParams()
    {
        $params = array();
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 3, \Lib\Util::getMessages() );
    }

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

    public function testNonExistingLoginEmail()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'mike@example.org',
            'password' => 'password' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    public function testBadLoginCredentials()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'mike@teachboost.com',
            'password' => 'password' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertFalse(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    public function testCorrectLoginCredentials()
    {
        \Lib\Util::clearMessages();
        $params = array(
            'email' => 'mikegioia+testteacher@gmail.com', // ID 3889
            'password' => 'bonner' );

        $this->assertCount( 0, \Lib\Util::getMessages() );
        $this->assertTrue(
            \Actions\Users\Auth::login( $params ) );
        $this->assertCount( 0, \Lib\Util::getMessages() );

        $session = DI::getDefault()->getSession();
        $this->assertTrue( valid( $session->get( 'user_id' ) ) );
    }

    public function testDestroyCookieSession()
    {
        $this->assertTrue(
            \Actions\Users\Auth::destroyToken( 3889 ) );
        $this->assertTrue(
            \Actions\Users\Auth::destroySession( 3889 ) );

        $session = DI::getDefault()->getSession();
        $this->assertFalse( valid( $session->get( 'user_id' ) ) );
    }
}

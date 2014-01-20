<?php

namespace Library;

class ValidateTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @group library
     * @group validate
     */
    public function testEmail()
    {
        $params = array(
            'email' => 'not an email' );
        \Lib\Validate::add(
            'email',
            array(
                'email' => array()
            ));

        \Lib\Util::clearMessages();
        $this->assertFalse( \Lib\Validate::run( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );

        $params = array(
            'email' => 'test@example.org' );
        \Lib\Validate::add(
            'email',
            array(
                'email' => array()
            ));

        \Lib\Util::clearMessages();
        $this->assertTrue( \Lib\Validate::run( $params ) );
        $this->assertCount( 0, \Lib\Util::getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testExists()
    {
        $params = array(
            'password' => 'password' );
        \Lib\Validate::add(
            'missing',
            array(
                'exists' => array()
            ));

        \Lib\Util::clearMessages();
        $this->assertFalse( \Lib\Validate::run( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );

        \Lib\Validate::add(
            'password',
            array(
                'exists' => array()
            ));

        \Lib\Util::clearMessages();
        $this->assertTrue( \Lib\Validate::run( $params ) );
        $this->assertCount( 0, \Lib\Util::getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testLength()
    {
        $params = array(
            'password' => 'abc' );
        \Lib\Validate::add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        \Lib\Util::clearMessages();
        $this->assertFalse( \Lib\Validate::run( $params ) );
        $this->assertCount( 1, \Lib\Util::getMessages() );

        $params = array(
            'password' => 'password1234' );
        \Lib\Validate::add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        \Lib\Util::clearMessages();
        $this->assertTrue( \Lib\Validate::run( $params ) );
        $this->assertCount( 0, \Lib\Util::getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testInvalidType()
    {
        try
        {
            \Lib\Validate::add(
                'test',
                array(
                    'missing' => array()
                ));
        }
        catch ( \Base\Exception $expected )
        {
            return;
        }

        $this->fail( "Invalid validation test exception wasn't raised." );
    }
}

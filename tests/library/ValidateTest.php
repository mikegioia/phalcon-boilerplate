<?php

namespace Library;

class ValidateTest extends \UnitTestCase
{
    /**
     * @group library
     * @group validate
     */
    public function testEmail()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'email' => 'not an email' );
        $validate->add(
            'email',
            array(
                'email' => array()
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $params = array(
            'email' => 'test@example.org' );
        $validate->add(
            'email',
            array(
                'email' => array()
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testExists()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'password' => 'password' );
        $validate->add(
            'missing',
            array(
                'exists' => array()
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $validate->add(
            'password',
            array(
                'exists' => array()
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testLength()
    {
        $validate = $this->di->get( 'validate' );
        $util = $this->di->get( 'util' );
        $params = array(
            'password' => 'abc' );
        $validate->add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        $this->assertFalse( $validate->run( $params ) );
        $this->assertCount( 1, $util->getMessages() );

        $params = array(
            'password' => 'password1234' );
        $validate->add(
            'password',
            array(
                'length' => array(
                    'min' => 6 )
            ));

        $util->clearMessages();
        $this->assertTrue( $validate->run( $params ) );
        $this->assertCount( 0, $util->getMessages() );
    }

    /**
     * @group library
     * @group validate
     */
    public function testInvalidType()
    {
        $validate = $this->di->get( 'validate' );

        try
        {
            $validate->add(
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

<?php

namespace Library;

class AuthTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        // log out after every function
        //
        \Lib\Auth::destroy();
    }

    /**
     * @group library
     * @group auth
     */
    public function testInit()
    {
        $this->assertFalse( \Lib\Auth::init() );
    }

    /**
     * @group library
     * @group auth
     */
    public function testBadManualLoading()
    {
        try
        {
            \Lib\Auth::load( NULL );
        }
        catch ( \Base\Exception $expected )
        {
            return;
        }

        $this->fail( "Invalid auth test exception wasn't raised." );
    }

    /**
     * @group library
     * @group auth
     */
    public function testManualLoading()
    {
        \Lib\Auth::load( 1 );

        $this->assertTrue( is_array( \Lib\Auth::getUser() ) );
    }
}

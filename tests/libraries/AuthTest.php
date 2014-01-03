<?php

namespace Libraries;

class AuthTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testInit()
    {
        $this->assertFalse( \Lib\Auth::init() );
    }

    public function testBadManualLoading()
    {
        try
        {
            \Lib\Auth::load();
        }
        catch ( \Base\Exception $expected )
        {
            return;
        }

        $this->fail( "Invalid auth test exception wasn't raised." );
    }

    public function testManualLoading()
    {
        \Lib\Auth::load( 1 );

        $this->assertTrue( is_array( \Lib\Auth::getUser() ) );
    }
}

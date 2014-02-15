<?php

namespace Library;

class AuthTest extends \UnitTestCase
{
    /**
     * @group library
     * @group auth
     */
    public function testInit()
    {
        $this->assertFalse( $this->di->get( 'auth' )->init() );
    }

    /**
     * @group library
     * @group auth
     */
    public function testBadManualLoading()
    {
        try
        {
            $this->di->get( 'auth' )->load( NULL );
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
        $auth = $this->di->get( 'auth' );
        $auth->load( 1 );

        $this->assertTrue( is_array( $auth->getUser() ) );
    }
}

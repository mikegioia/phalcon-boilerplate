<?php

namespace Library;

class CacheTest extends \UnitTestCase
{
    /**
     * @group library
     * @group cache
     */
    public function testGetSet()
    {
        $test = $this->di->get( 'cache' )->getSet(
            'test',
            function () {
                return 'works';
            });
        $this->assertEquals( $test, 'works' );
    }
}

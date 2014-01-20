<?php

namespace Library;

class CacheTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @group library
     * @group cache
     */
    public function testGetSet()
    {
        $test = \Lib\Cache::getSet(
            'test',
            function () {
                return 'works';
            });
        $this->assertEquals( $test, 'works' );
    }
}

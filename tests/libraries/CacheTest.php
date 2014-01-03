<?php

namespace Libraries;

class CacheTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

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

<?php

namespace Library;

class UtilTest extends \UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @group library
     * @group util
     */
    public function testRemoveMessage()
    {
        $this->assertCount( 0, $this->di->get( 'util' )->getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testAddMessage()
    {
        $util = $this->di->get( 'util' );
        $util->addMessage( 'test message', SUCCESS );
        $this->assertCount( 1, $util->getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testBenchmarks()
    {
        $util = $this->di->get( 'util' );
        $util->startBenchmark();
        usleep( 25 );
        $util->stopBenchmark();
        $debugInfo = $util->getDebugInfo();

        $this->assertCount( 8, $debugInfo );
        $this->assertArrayHasKey( 'memory', $debugInfo );
        $this->assertArrayHasKey( 'time', $debugInfo );
        $this->assertGreaterThan( 0, $debugInfo[ 'memory' ] );
        $this->assertGreaterThan( 0, $debugInfo[ 'time' ] );

        $util->resetBenchmarks();
        $debugInfo = $util->getDebugInfo();

        $this->assertEquals( 0, $debugInfo[ 'memory' ] );
        $this->assertEquals( 0, $debugInfo[ 'time' ] );
    }
}

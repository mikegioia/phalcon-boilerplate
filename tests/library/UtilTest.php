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
        \Lib\Util::clearMessages();
        $this->assertCount( 0, \Lib\Util::getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testAddMessage()
    {
        \Lib\Util::addMessage( 'test message', SUCCESS );
        $this->assertCount( 1, \Lib\Util::getMessages() );
    }

    /**
     * @group library
     * @group util
     */
    public function testBenchmarks()
    {
        \Lib\Util::startBenchmark();
        usleep( 25 );
        \Lib\Util::stopBenchmark();
        $debugInfo = \Lib\Util::getDebugInfo();

        $this->assertCount( 8, $debugInfo );
        $this->assertArrayHasKey( 'memory', $debugInfo );
        $this->assertArrayHasKey( 'time', $debugInfo );
        $this->assertGreaterThan( 0, $debugInfo[ 'memory' ] );
        $this->assertGreaterThan( 0, $debugInfo[ 'time' ] );

        \Lib\Util::resetBenchmarks();
        $debugInfo = \Lib\Util::getDebugInfo();

        $this->assertEquals( 0, $debugInfo[ 'memory' ] );
        $this->assertEquals( 0, $debugInfo[ 'time' ] );
    }
}

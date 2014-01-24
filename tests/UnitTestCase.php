<?php

use Phalcon\DI as DI,
    Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    protected function setUp()
    {
        // create a new DI container
        //
        $bootstrap = new \Lib\Bootstrap\Unit(
            array(
                'url', 'cookies', 'session', 'profiler', 'db',
                'mongo', 'collectionManager', 'dataCache',
                'util', 'auth', 'validate', 'cache'
            ));
        $bootstrap->run();

        parent::setUp( $bootstrap->getDI() );
    }
}

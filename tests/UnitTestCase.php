<?php

use Phalcon\DI as DI,
    Phalcon\Test\UnitTestCase as PhalconTestCase,
    Phalcon\Config,
    Phalcon\DiInterface as DIC;

abstract class UnitTestCase extends PhalconTestCase
{
    protected function setUp( DIC $di = NULL, Config $config = NULL )
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

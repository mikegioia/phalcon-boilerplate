<?php

use Phalcon\DI as DI,
    Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    protected function setUp()
    {
        $di = DI::getDefault();

        // override the cookie service
        //
        $di->setShared(
            'cookies',
            function() {
                $cookies = new \Lib\Mocks\Cookies();
                $cookies->useEncryption( FALSE );
                return $cookies;
            });

        parent::setUp( $di );
    }
}

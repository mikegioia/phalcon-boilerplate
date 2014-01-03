<?php

use Phalcon\DI as DI,
    Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    private $_loaded = FALSE;

    protected function setUp()
    {
        $di = DI::getDefault();

        parent::setUp( $di );

        $this->_loaded = TRUE;
    }

    public function __destruct()
    {
        if ( ! $this->_loaded )
        {
            throw new \PHPUnit_Framework_IncompleteTestError(
                'Please run parent::setUp().' );
        }
    }
}

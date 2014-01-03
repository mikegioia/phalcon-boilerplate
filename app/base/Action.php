<?php

namespace Base;

use \Phalcon\DI as DI;

class Action extends \Phalcon\Mvc\User\Component
{
    public function getService( $service )
    {
        $func = "get". ucfirst( $service );

        return DI::getDefault()->$func();
    }
}
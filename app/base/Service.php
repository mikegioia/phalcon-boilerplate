<?php

namespace Base;

use Phalcon\DI as DI;

class Service extends \Phalcon\Mvc\User\Component
{
    function getService( $service )
    {
        return $this->getDI()->get( $service );
    }

    static function getStaticService( $service )
    {
        return DI::getDefault()->get( $service );
    }
}
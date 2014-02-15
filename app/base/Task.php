<?php

namespace Base;

use \Phalcon\DI as DI;

class Task extends \Phalcon\CLI\Task
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
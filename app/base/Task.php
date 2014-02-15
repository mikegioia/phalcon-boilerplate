<?php

namespace Base;

class Task extends \Phalcon\CLI\Task
{
    public function getService( $service )
    {
        return $this->getDI()->get( $service );
    }
}
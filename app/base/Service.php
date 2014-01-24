<?php

namespace Base;

class Service extends \Phalcon\Mvc\User\Component
{
    public function getService( $service )
    {
        return $this->getDI()->get( $service );
    }
}
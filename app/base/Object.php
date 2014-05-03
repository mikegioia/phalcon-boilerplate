<?php

namespace Base;

class Object
{
    public function __construct( $params = array() )
    {
        foreach ( $params as $name => $value )
        {
            $this->$name = $value;
        }
    }
}

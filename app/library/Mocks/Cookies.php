<?php

namespace Lib\Mocks;

/**
 * Mock the set and get cookie methods for unit testing
 */
class Cookies extends \Phalcon\Http\Response\Cookies
{
    private $jar = array();

    public function set( 
        $name, 
        $value = '',
        $expire = 0,
        $path = '/',
        $secure = FALSE,
        $domain = '',
        $httpOnly = FALSE )
    {
        $this->jar[ $name ] = new \Lib\Mocks\Cookie(
            $name, $value, $expire,
            $path, $secure, $domain,
            $httpOnly );
        return TRUE;
    }

    public function get( $name )
    {
        return ( isset( $this->jar[ $name ] ) )
            ? $this->jar[ $name ]
            : new Cookie();
    }

    public function has( $name )
    {
        return isset( $this->jar[ $name ] );
    }

    public function delete( $name )
    {
        unset( $this->jar[ $name ] );
        return TRUE;
    }
}

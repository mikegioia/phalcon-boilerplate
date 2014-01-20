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

class Cookie extends \Lib\Mocks\Cookies
{
    public $name;
    public $value;
    public $expire;
    public $path;
    public $secure;
    public $domain;
    public $httpOnly;

    public function __construct(
        $name = '',
        $value = '',
        $expire = '',
        $path = '/',
        $secure = FALSE,
        $domain = '',
        $httpOnly = FALSE )
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->secure = $secure;
        $this->domain = $domain;
        $this->httpOnly = $httpOnly;
    }

    public function delete( $name = NULL )
    {
        return parent::delete( $this->name );
    }

    public function getValue()
    {
        return $this->value;
    }
}
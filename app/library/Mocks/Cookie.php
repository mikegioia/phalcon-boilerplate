<?php

namespace Lib\Mocks;

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
<?php

namespace Lib\Mocks;

/**
 * Mock the session library
 */
class Session
{
    protected $sessionId;
    protected $session;
    protected $isStarted;

    public function __construct( $options = NULL )
    {
        $this->session = array();
        $this->isStarted = TRUE;
        $this->sessionId = uniqid();

        session_set_save_handler(
            array( $this, 'open' ),
            array( $this, 'close' ),
            array( $this, 'read' ),
            array( $this, 'write' ),
            array( $this, 'destroy' ),
            array( $this, 'gc' ));
    }

    public function getId()
    {
        return $this->sessionId;
    }

    public function open()
    {
        return TRUE;
    }

    public function close()
    {
        return FALSe;
    }

    public function read( $sessionId = NULL )
    {
        return $this->session;
    }

    public function write( $sessionId, $data )
    {
        $this->session = $data;
    }

    public function set( $keyName, $content )
    {
        $this->session[ $keyName ] = $content;
    }

    public function get( $keyName, $default = NULL )
    {
        return isset( $this->session[ $keyName ] )
            ? $this->session[ $keyName ]
            : $default;
    }

    public function has( $keyName )
    {
        return isset( $this->session[ $keyName ] );
    }

    public function remove( $keyName )
    {
        unset( $this->session[ $keyName ] );
    }

    public function destroy()
    {
        $this->session = array();
        return TRUE;
    }

    public function start()
    {
        $this->isStarted = TRUE;
        return FALSE;
    }

    public function isStarted()
    {
        return $this->isStarted;
    }

    public function gc() {}
}
<?php

namespace Lib\Mocks;

/**
 * Mock the data cache service. This is a simple key/value store.
 */
class DataCache
{
    protected $cache;

    public function __construct()
    {
        $this->cache = array();
    }

    public function get( $keyName, $lifetime = NULL )
    {
        return ( isset( $this->cache[ $keyName ] ) )
            ? $this->cache[ $keyName ]
            : NULL;
    }

    public function save( $keyName = NULL, $content = NULL, $lifetime = NULL, $stopBuffer = TRUE )
    {
        $this->cache[ $keyName ] = $content;
    }

    public function delete( $keyName )
    {
        unset( $this->cache[ $keyName ] );
        return TRUE;
    }

    public function queryKeys( $prefix = NULL )
    {
        return array();
    }

    public function exists( $keyName = NULL, $lifetime = NULL )
    {
        return isset( $this->cache[ $keyName ] );
    }
}

<?php

namespace Lib;

/**
 * Internal cache management service
 */
class Cache extends \Base\Library
{
    /**
     * Return the data if the key exists. if not, run the callback and
     * set the key to the callback's response.
     *
     * @param string $keyName
     * @param function $callback
     * @param int $lifetime
     * @return mixed
     */
    public static function getSet( $keyName, $callback = NULL, $lifetime = NULL )
    {
        $cache = self::getService( 'cache' );

        if ( $cache->exists( $keyName ) )
        {
            return $cache->get( $keyName );
        }

        if ( ! is_callable( $callback ) )
        {
            return NULL;
        }

        $content = $callback();
        $cache->save( $keyName, $content, $lifetime );

        // @TODO if save failed, log error

        return $content;
    }
}

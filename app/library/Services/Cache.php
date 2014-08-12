<?php

namespace Lib\Services;

/**
 * Internal cache management service
 *
 * @depends service dataCache
 */
class Cache extends \Base\Service
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
    public function getSet( $keyName, $callback = NULL, $lifetime = NULL )
    {
        $cache = $this->getDI()->getShared( 'dataCache' );

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
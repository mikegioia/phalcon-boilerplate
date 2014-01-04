<?php

namespace Lib;

/**
 * Internal utility component
 */
class Util extends \Base\Library
{
    private static $messages = array();
    private static $memory = array();
    private static $time = array();

    public static function addMessage( $message, $type = SUCCESS )
    {
        self::$messages[] = array( $type => $message );
    }

    public static function getMessages()
    {
        return self::$messages;
    }

    public static function setFlash( $messages )
    {
        $session = self::getService( 'session' );
        $flash = $session->get( 'flash' );

        if ( ! is_array( $flash ) )
        {
            $flash = array();
        }

        $flash = array_merge( $flash, $messages );
        $session->set( 'flash', $flash );
    }

    public static function getFlash()
    {
        $session = self::getService( 'session' );
        $flash = $session->get( 'flash' );
        $session->remove( 'flash' );

        return $flash;
    }

    public static function clearMessages()
    {
        self::$messages = array();
    }

    public static function startBenchmark()
    {
        self::$memory[ 'start' ] = memory_get_usage();
        self::$time[ 'start' ] = microtime( TRUE );
    }

    public static function stopBenchmark()
    {
        self::$memory[ 'end' ] = memory_get_usage();
        self::$time[ 'end' ] = microtime( TRUE );
    }

    public static function resetBenchmarks()
    {
        self::$memory = array(
            'start' => 0,
            'end' => 0 );
        self::$time = array(
            'start' => 0,
            'end' => 0 );
    }

    public static function getMemoryUsage()
    {
        return self::$memory[ 'end' ] - self::$memory[ 'start' ];
    }

    public static function getPeakMemoryUsage()
    {
        return memory_get_peak_usage();
    }

    public static function getExecutionTime()
    {
        return self::$time[ 'end' ] - self::$time[ 'start' ];
    }

    public static function getQueryProfiles()
    {
        $profiler = self::getService( 'profiler' );
        $profiles = $profiler->getProfiles();
        $return = array();

        foreach ( (array) $profiles as $profile )
        {
            $return[] = array(
                'statement' => $profile->getSQLStatement(),
                'start_time' => $profile->getInitialTime(),
                'end_time' => $profile->getFinalTime(),
                'elapsed_time' => $profile->getTotalElapsedSeconds() );
        }

        return $return;
    }

    public function getDebugInfo()
    {
        return array(
            'session_id' => self::getService( 'session' )->getId(),
            'memory' => self::getMemoryUsage(),
            'memory_human' => human_bytes( self::getMemoryUsage() ),
            'peak_memory' => self::getPeakMemoryUsage(),
            'peak_memory_human' => human_bytes( self::getPeakMemoryUsage() ),
            'time' => self::getExecutionTime(),
            'time_human' => round( self::getExecutionTime() * 1000, 2 ) .' ms',
            'query_profiles' => self::getQueryProfiles() );
    }
}

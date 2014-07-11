<?php

namespace Lib\Services;

/**
 * Internal utility component
 *
 * @depends service session
 * @depends service profiler
 */
class Util extends \Base\Service
{
    protected $messages = array();
    protected $memory = array(
        'start' => 0,
        'end' => 0 );
    protected $time = array(
        'start' => 0,
        'end' => 0 );

    public function addMessage( $message, $type = SUCCESS )
    {
        if ( is_array( $message ) )
        {
            foreach ( $message as $m )
            {
                $this->messages[] = array( $type => $m );
            }
        }
        else
        {
            $this->messages[] = array( $type => $message );
        }
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setFlash( $messages )
    {
        $session = $this->getService( 'session' );
        $flash = $session->get( 'flash' );

        if ( ! is_array( $flash ) )
        {
            $flash = array();
        }

        $flash = array_merge( $flash, $messages );
        $session->set( 'flash', $flash );
    }

    public function getFlash()
    {
        $session = $this->getService( 'session' );
        $flash = $session->get( 'flash' );
        $session->remove( 'flash' );

        return $flash;
    }

    public function clearMessages()
    {
        $this->messages = array();
    }

    public function startBenchmark()
    {
        $this->memory[ 'start' ] = memory_get_usage();
        $this->time[ 'start' ] = microtime( TRUE );
    }

    public function stopBenchmark()
    {
        $this->memory[ 'end' ] = memory_get_usage();
        $this->time[ 'end' ] = microtime( TRUE );
    }

    public function resetBenchmarks()
    {
        $this->memory = array(
            'start' => 0,
            'end' => 0 );
        $this->time = array(
            'start' => 0,
            'end' => 0 );
    }

    public function getMemoryUsage()
    {
        return $this->memory[ 'end' ] - $this->memory[ 'start' ];
    }

    public function getPeakMemoryUsage()
    {
        return memory_get_peak_usage();
    }

    public function getExecutionTime()
    {
        return $this->time[ 'end' ] - $this->time[ 'start' ];
    }

    public function getQueryProfiles()
    {
        $profiler = $this->getService( 'profiler' );
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
            'session_id' => $this->getService( 'session' )->getId(),
            'memory' => $this->getMemoryUsage(),
            'memory_human' => human_bytes( $this->getMemoryUsage() ),
            'peak_memory' => self::getPeakMemoryUsage(),
            'peak_memory_human' => human_bytes( $this->getPeakMemoryUsage() ),
            'time' => $this->getExecutionTime(),
            'time_human' => round( $this->getExecutionTime() * 1000, 2 ) .' ms',
            'query_profiles' => $this->getQueryProfiles() );
    }
}

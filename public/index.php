<?php

// set up the environment
date_default_timezone_set( 'UTC' );
define( 'APP_PATH', __DIR__ . '/../app' );
define( 'VENDOR_PATH', __DIR__ .'/../vendor' );

include APP_PATH . '/etc/constants.php';
include APP_PATH . '/etc/helpers.php';
include APP_PATH . '/library/Bootstrap/Base.php';
include APP_PATH . '/library/Bootstrap/App.php';

// run the application
try
{
    // bootstrap the application
    $bootstrap = new \Lib\Bootstrap\App(
        array(
            'router', 'url', 'cookies', 'session',
            'profiler', 'db', 'mongo', 'collectionManager',
            'dataCache', 'view', 'dispatcher', 'util', 'auth',
            'validate', 'cache'
        ));
    $bootstrap->run();
}
catch ( Exception $e )
{
    echo get_stack_trace( $e );
}

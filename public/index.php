<?php

// set up the environment
//
date_default_timezone_set( 'UTC' );
define( 'APP_PATH', __DIR__ . '/../app' );
define( 'VENDOR_PATH', __DIR__ .'/../vendor' );

// run the application
//
try
{
    // read the configuration
    //
    $config = new \Phalcon\Config\Adapter\Ini(
        APP_PATH . '/config/config.ini' );

    // merge in our local config settings
    //
    $localConfig = new \Phalcon\Config\Adapter\Ini(
        APP_PATH . '/config/config.local.ini' );
    $config->merge( $localConfig );

    // set up error reporting
    //
    if ( $config->app->errorReporting ):
        error_reporting( E_ALL );
        ini_set( 'display_errors', 1 );
    else:
        error_reporting( 0 );
        ini_set( 'display_errors', 0 );
    endif;

    // read the autoloader
    //
    include APP_PATH . '/config/loader.php';

    // initialize our benchmarks
    //
    \Lib\Util::startBenchmark();

    // read services
    //
    include APP_PATH . '/config/services.php';
    \Phalcon\DI::setDefault( $di );

    // load our constants
    //
    include APP_PATH . '/config/constants.php';

    // load the helpers
    //
    include APP_PATH . '/config/helpers.php';

    // handle the request
    //
    $application = new Phalcon\Mvc\Application( $di );

    // run auth init
    //
    \Lib\Auth::init();

    // output the content. our benchmark is finished in the base
    // controller before output is sent.
    //
    echo $application->handle()->getContent();
}
catch( \Phalcon\Exception $e )
{
    echo 'PhalconException: ', $e->getMessage(), '<br />';
    echo nl2br( htmlentities( $e->getTraceAsString() ) );
}
catch ( PDOException $e )
{
    echo 'PDOException: ', $e->getMessage(), '<br />';
    echo nl2br( htmlentities( $e->getTraceAsString() ) );
}

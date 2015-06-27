<?php

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
date_default_timezone_set( 'UTC' );

define( 'ROOT_PATH', __DIR__ );
define( 'APP_PATH', __DIR__ . '/../app' );
define( 'CLI_PATH', __DIR__ . '/../cli' );
define( 'VENDOR_PATH', __DIR__ . '/../vendor' );

// App constants and functions (global)
include APP_PATH . '/etc/constants.php';
include APP_PATH . '/etc/helpers.php';

// Bootstrap and unit test classes
include APP_PATH . '/library/Bootstrap/Base.php';
include APP_PATH . '/library/Bootstrap/Cli.php';

// Read in the command line arguments to run
$arguments = [
    'task' => NULL,
    'action' => NULL,
    'params' => [] ];

foreach ( $argv as $k => $arg ):
    if ( $k == 1 ):
        $arguments[ 'task' ] = $arg;
    elseif ( $k == 2 ):
        $arguments[ 'action' ] = $arg;
    elseif ( $k >= 3 ):
        $arguments[ 'params' ][] = $arg;
    endif;
endforeach;

// Bootstrap the application
$bootstrap = new \Lib\Bootstrap\Cli([
    'router', 'url', 'profiler', 'db', 'mongo',
    'collectionManager', 'dataCache', 'util',
    'auth', 'validate', 'cache' ]);

try
{
    $bootstrap->run( $arguments );
}
catch ( \Exception $e )
{
    echo "Error:\n", $e->getMessage(), "\n\n";
    echo "Stack trace:\n", $e->getTraceAsString(), "\n";
}
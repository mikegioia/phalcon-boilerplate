<?php

date_default_timezone_set( 'UTC' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

define( 'ROOT_PATH', __DIR__ );
define( 'APP_PATH', __DIR__ . '/../app' );
define( 'VENDOR_PATH', __DIR__ . '/../vendor' );
set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path() );

// Vendor autoload
include ROOT_PATH . "/../vendor/autoload.php";

// App constants and functions (global)
include APP_PATH . '/etc/constants.php';
include APP_PATH . '/etc/helpers.php';

// Bootstrap and unit test classes
include APP_PATH . '/library/Bootstrap/Base.php';
include APP_PATH . '/library/Bootstrap/Unit.php';
include ROOT_PATH . "/UnitTestCase.php";
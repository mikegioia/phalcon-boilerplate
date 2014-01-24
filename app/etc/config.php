<?php

return array(
    'app' => array(
        'environment' => 'local',
        'errorReporting' => TRUE,
        // can be 'api' or 'view'
        'responseMode' => 'api',
        // router namespace modules
        'modules' => array(
            'api' => 'Api' )),

    'paths' => array(
        'baseUri' => 'http://phalcon.dev/',
        'assetUri' => 'http://phalcon.dev/',
        'hostname' => 'phalcon.dev' ),

    'session' => array(
        // can be 'redis' or 'files'
        'adapter' => 'files',
        'name' => 'phalcon',
        'lifetime' => 1440,
        'cookieLifetime' => 86400 ),

    'cache' => array(
        // can be 'redis' or 'files'
        'adapter' => 'files',
        'prefix' => '',
        // only used for files adapter
        // should have web user group write
        'dir' => '/tmp/' ),

    'cookies' => array(
        // 14 days
        'expire' => 60*60*24*14,
        'path' => '/',
        'secure' => TRUE,
        'httpOnly' => TRUE ),

    'redis' => array(
        'cache' => array(
            'host' => 'localhost',
            'port' => 6379 ),
        'session' => array(
            'host' => 'localhost',
            'port' => 6379,
            'prefix' => 'session:' )),

    'database' => array(
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'phalcon',
        'persistent' => TRUE ),

    'mongodb' => array(
        'host' => 'localhost',
        'port' => 27017,
        'username' => '',
        'password' => '',
        'dbname' => 'phalcon' ),

    'profiling' => array(
        'system' => TRUE,
        'query' => TRUE ),

    'settings' => array(
        'cookieToken' => 'cookie_token'
    ));
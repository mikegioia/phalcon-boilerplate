<?php

return array(
    'app' => array(
        'environment' => 'local',
        'responseMode' => 'view' ),

    'database' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root' ),

    'cache' => array(
        'adapter' => 'redis' ),

    'paths' => array(
        'baseUri' => 'http://phalcon.dev/',
        'assetUri' => 'http://phalcon.dev/',
        'hostname' => 'phalcon.dev' ),

    'cookies' => array(
        'secure' => TRUE ),

    'profiling' => array(
        'query' => FALSE
    ));

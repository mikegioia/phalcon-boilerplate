<?php

return array(
    'app' => array(
        'environment' => 'local',
        'responseMode' => 'view' ),

    'cache' => array(
        'adapter' => 'redis' ),

    'paths' => array(
        'baseUri' => 'http://phalcon.dev/phalconbp/',
        'assetUri' => 'http://phalcon.dev/phalconbp/',
        'hostname' => 'phalcon.dev' ),

    'cookies' => array(
        'secure' => TRUE ),

    'profiling' => array(
        'query' => FALSE
    ));

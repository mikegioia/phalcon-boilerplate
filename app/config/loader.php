<?php

$loader = new Phalcon\Loader();

$loader->registerNamespaces(
    array(
        'Actions' => APP_PATH .'/actions/',
        'Base' => APP_PATH .'/base/',
        'Controllers' => APP_PATH .'/controllers/',
        'Db' => APP_PATH .'/models/',
        'Lib' => APP_PATH .'/library/',
        'Phalcon' => VENDOR_PATH .'/phalcon/incubator/Library/Phalcon/'
    ));

$loader->registerClasses(
    array(
        '__' => VENDOR_PATH .'/Underscore.php'
    ));

$loader->register();
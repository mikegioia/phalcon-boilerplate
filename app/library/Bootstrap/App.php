<?php

namespace Lib\Bootstrap;

use Phalcon\Mvc\Application,
    Phalcon\Loader as Loader;

class App extends \Lib\Bootstrap\Base
{
    public function __construct( $services = array() )
    {
        parent::__construct( $services );
    }

    public function run( $args = array() )
    {
        parent::run( $args );

        // initialize our benchmarks
        $this->di[ 'util' ]->startBenchmark();

        // create the mvc application
        $application = new Application( $this->di );

        // run auth init
        $this->di[ 'auth' ]->init();

        // output the content. our benchmark is finished in the base
        // controller before output is sent.
        echo $application->handle()->getContent();
    }

    /**
     * Register the namespaces, classes and directories
     */
    protected function initLoader()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'Actions' => APP_PATH .'/actions/',
            'Base' => APP_PATH .'/base/',
            'Controllers' => APP_PATH .'/controllers/',
            'Db' => APP_PATH .'/models/',
            'Lib' => APP_PATH .'/library/',
            'Phalcon' => VENDOR_PATH .'/phalcon/incubator/Library/Phalcon/'
        ]);
        $loader->registerClasses([
            '__' => VENDOR_PATH .'/Underscore.php'
        ]);
        $loader->register();

        $this->di[ 'loader' ] = $loader;
    }

    protected function initConfig()
    {
        parent::initConfig();

        // set up error reporting
        $config = $this->di[ 'config' ];

        if ( $config->app->errorReporting ):
            error_reporting( E_ALL );
            ini_set( 'display_errors', 1 );
        else:
            error_reporting( 0 );
            ini_set( 'display_errors', 0 );
        endif;
    }
}
<?php

namespace Lib\Bootstrap;

use Phalcon\Mvc\Application,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Dispatcher;

class App extends \Lib\Bootstrap\Base
{
    public function __construct( $services = array() )
    {
        parent::__construct( $services );
    }

    public function run()
    {
        parent::run();

        // initialize our benchmarks
        //
        $this->di[ 'util' ]->startBenchmark();

        // create the mvc application
        //
        $application = new Application( $this->di );

        // run auth init
        //
        $this->di[ 'auth' ]->init();

        // output the content. our benchmark is finished in the base
        // controller before output is sent.
        //
        echo $application->handle()->getContent();
    }

    protected function initConfig()
    {
        parent::initConfig();

        // set up error reporting
        //
        $config = $this->di[ 'config' ];

        if ( $config->app->errorReporting ):
            error_reporting( E_ALL );
            ini_set( 'display_errors', 1 );
        else:
            error_reporting( 0 );
            ini_set( 'display_errors', 0 );
        endif;
    }

    protected function initView()
    {
        $config = $this->di[ 'config' ];

        $this->di->set(
            'view', 
            function () use ( $config ) {
                $view = new View();
                $view->setViewsDir( APP_PATH .'/views/' );
                return $view;
            },
            TRUE );
    }

    protected function initDispatcher()
    {
        $eventsManager = $this->di[ 'eventsManager' ];

        $this->di->set(
            'dispatcher',
            function () use ( $eventsManager ) {
                // create the default namespace
                //
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace( 'Controllers' );

                // set up our error handler
                //
                $eventsManager->attach(
                    'dispatch:beforeException',
                    function ( $event, $dispatcher, $exception ) {
                        switch ( $exception->getCode() )
                        {
                            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                                $dispatcher->forward(
                                    array(
                                        'namespace' => 'Controllers',
                                        'controller' => 'error',
                                        'action' => 'show404'
                                    ));
                                return FALSE;
                        }
                    });

                $dispatcher->setEventsManager( $eventsManager );

                return $dispatcher;
            },
            TRUE );
    }
}
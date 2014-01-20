<?php

use Phalcon\Loader,
    Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\Application,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

use Lib\Auth;

// set up all default phalcon services. to initialize a raw
// DI object, call $di = new Phalcon\DI();
//
$di = new FactoryDefault();

// set config in the container
//
$di->set( 'config', $config );

// load our routes
//
$di->set(
    'router',
    function () use ( $config ) {
        return require APP_PATH . '/config/routes.php';
    },
    TRUE );

// the URL component is used to generate all kind of urls in 
// the application
//
$di->set(
    'url',
    function () use ( $config ) {
        $url = new UrlResolver();
        $url->setBaseUri( $config->paths->baseUri );
        return $url;
    },
    TRUE );

// disable cookie encryption
//
$di->set(
    'cookies',
    function() {
        $cookies = new Phalcon\Http\Response\Cookies();
        $cookies->useEncryption( FALSE );
        return $cookies;
    },
    TRUE );

// set up our session. redis and native sessions are supported
//
$di->set(
    'session',
    function () use ( $config ) {
        if ( $config->session->adapter === 'redis' ):
            $session = new Phalcon\Session\Adapter\Redis(
                array(
                    'path' => sprintf(
                        'tcp://%s:%s?weight=1&prefix=%s',
                        $config->redis->session->host,
                        $config->redis->session->port,
                        $config->redis->session->prefix ),
                    'name' => $config->session->name,
                    'lifetime' => $config->session->lifetime,
                    'cookie_lifetime' => $config->session->cookieLifetime
                ));
        else:
            $session = new Phalcon\Session\Adapter\Files();
        endif;

        $session->start();
        return $session;
    },
    TRUE );

// query profiler
//
$di->set(
    'profiler',
    function () {
        return new \Phalcon\Db\Profiler();
    },
    TRUE );

// mysql connection
//
$di->set(
    'db',
    function () use ( $config, $di ) {
        // set up the database adapter
        //
        $adapter = new DbAdapter(
            array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'persistent' => $config->database->persistent
            ));

        if ( $config->profiling->query )
        {
            $eventsManager = new \Phalcon\Events\Manager();
            $profiler = $di->getProfiler();

            // listen to all the database events
            //
            $eventsManager->attach(
                'db',
                function ( $event, $connection ) use ( $profiler ) {
                    if ( $event->getType() == 'beforeQuery' ):
                        $profiler->startProfile( $connection->getSQLStatement() );
                    endif;

                    if ( $event->getType() == 'afterQuery' ):
                        $profiler->stopProfile();
                    endif;
                });
            $adapter->setEventsManager( $eventsManager );
        }

        return $adapter;
    },
    TRUE );

// mongodb connection
//
$di->set(
    'mongo',
    function () use ( $config ) {
        $mongo = new Mongo();
        return $mongo->selectDb(
            $config->mongodb->dbname );
    },
    TRUE );

// collection manager
//
$di->set(
    'collectionManager',
    function(){
        return new Phalcon\Mvc\Collection\Manager();
    },
    TRUE );

// local redis cache
//
$di->set(
    'cache',
    function () use ( $config ) {
        // create a Data frontend and set a default lifetime to 1 hour
        //
        $frontend = new \Phalcon\Cache\Frontend\Data(
            array(
                'lifetime' => 3600
            ));

        if ( $config->session->adapter === 'redis' ):
            // connect to redis
            //
            $redis = new Redis();
            $redis->connect(
                $config->redis->cache->host,
                $config->redis->cache->port );

            // create the cache passing the connection
            //
            return new \Phalcon\Cache\Backend\Redis(
                $frontend,
                array(
                    'redis' => $redis
                ));
        else:
            return new \Phalcon\Cache\Backend\File(
                $frontend,
                array(
                    'cacheDir' => $config->cache->dir,
                    'prefix' => $config->cache->prefix
                ));
        endif;
    },
    TRUE );

// set up the view component
//
$di->set(
    'view', 
    function () use ( $di, $config ) {
        $view = new View();
        $view->setViewsDir( APP_PATH .'/views/' );
        return $view;
    },
    TRUE );

// set up the dispatcher
//
$di->set(
    'dispatcher',
    function () use ( $di ) {
        // create the default namespace
        //
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace( 'Controllers' );

        // set up our error handler
        //
        $eventsManager = $di->getShared( 'eventsManager' );
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

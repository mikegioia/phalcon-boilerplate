<?php

namespace Lib\Bootstrap;

use Phalcon\Loader as Loader;

class Unit extends \Lib\Bootstrap\Base
{
    public function __construct( $services = array() )
    {
        parent::__construct( $services );
    }

    public function run( $args = array() )
    {
        parent::run( $args );

        // start the session
        //
        if ( ! $this->di[ 'session' ]->isStarted() ):
            $this->di[ 'session' ]->start();
        endif;
    }

    protected function initLoader()
    {
        $loader = new Loader();
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
        $loader->registerDirs(
            array(
                ROOT_PATH
            ));
        $loader->register();

        $this->di[ 'loader' ] = $loader;
    }

    protected function initCookies()
    {
        $this->di->set(
            'cookies',
            function() {
                return new \Lib\Mocks\Cookies();
            },
            TRUE );
    }

    protected function initSession()
    {
        $this->di->set(
            'session',
            function() {
                return new \Lib\Mocks\Session();
            },
            TRUE );
    }

    protected function initDataCache()
    {
        $this->di->set(
            'dataCache',
            function() {
                return new \Lib\Mocks\DataCache();
            },
            TRUE );
    }
}
<?php

namespace Lib\Bootstrap;

use Phalcon\Loader as Loader,
    Phalcon\DI\FactoryDefault\CLI as CliDI;

class Cli extends \Lib\Bootstrap\Base
{
    public function __construct( $services = array() )
    {
        $this->di = new CliDI();
        $this->services = $services;
    }

    public function run( $args = array() )
    {
        parent::run( $args );

        // call the task action specified
        $class = '\Tasks\\'. ucfirst( $args[ 'task' ] ) .'Task';
        $action = strtolower( $args[ 'action' ] ) .'Action';
        $task = new $class();

        return call_user_func_array(
            [ $task, $action ],
            $args[ 'params' ] );
    }

    protected function initLoader()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'Actions' => APP_PATH .'/actions/',
            'Base' => APP_PATH .'/base/',
            'Controllers' => APP_PATH .'/controllers/',
            'Db' => APP_PATH .'/models/',
            'Lib' => APP_PATH .'/library/',
            'Tasks' => CLI_PATH .'/tasks/'
        ]);
        $loader->registerClasses([
            '__' => VENDOR_PATH .'/Underscore.php'
        ]);
        $loader->register();

        // autoload vendor dependencies
        require_once VENDOR_PATH .'/autoload.php';

        $this->di[ 'loader' ] = $loader;
    }
}

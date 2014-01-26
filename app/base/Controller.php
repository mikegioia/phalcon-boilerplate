<?php

namespace Base;

use Phalcon\Mvc\View;

class Controller extends \Phalcon\Mvc\Controller
{
    // response variables
    //
    public $status = SUCCESS;
    public $code = 200;
    public $messages = array();
    public $data = NULL;

    protected $checkLoggedIn = TRUE;
    protected $responseMode = NULL;
    protected $redirect = NULL;

    public function initialize()
    {
        $this->data = new \stdClass();
    }

    /**
     * Check if the user is logged in unless the controller specifically
     * requests that we don't.
     */
    public function beforeExecuteRoute()
    {
        if ( is_null( $this->responseMode ) )
        {
            $this->responseMode = $this->config->app->responseMode;
        }

        if ( $this->checkLoggedIn )
        {
            return $this->checkLoggedIn();
        }
    }

    /**
     * For all requests we want to pull any messages from the Util
     * library. For API mode, after route execution we want to handle
     * requests by outputting a json encoded object with our response
     * data.
     */
    public function afterExecuteRoute()
    {
        $util = $this->getDI()->getShared( 'util' );

        // pull stored messages and stop the benchmarks
        //
        $this->messages = array_merge(
            $this->messages,
            $util->getMessages() );

        $util->stopBenchmark();

        // if we're in API mode, send a JSON response. otherwise
        // save the messages to the flashdata and optionally redirect
        // if the redirect is set.
        //
        if ( $this->responseMode === 'api' )
        {
            $response = array(
                'status' => $this->status,
                'code' => $this->code,
                'messages' => $this->messages,
                'data' => $this->data );

            // if profiling is enabled, pull the benchmark data
            //
            if ( $this->config->profiling->system )
            {
                $response[ 'debug' ] = $util->getDebugInfo();
            }

            $this->view->disable();

            // set the json headers and store our response in the view
            //
            $this->response->resetHeaders();
            $this->response->setContentType( 'application/json' );
            $this->response->setJsonContent( $response );
            $this->response->send();
        }
        else
        {
            $util->setFlash( $this->messages );

            if ( ! is_null( $this->redirect ) )
            {
                return $this->response->redirect( $this->redirect );
            }
            else
            {
                $this->view->flashMessages = $util->getFlash();
            }
        }
    }

    public function checkLoggedIn()
    {
        $auth = $this->getDI()->getShared( 'auth' );

        if ( ! $auth->isLoggedIn() )
        {
            if ( $this->responseMode === 'api' )
            {
                return $this->dispatcher->forward(
                    array(
                        'namespace' => 'Controllers',
                        'controller' => 'error',
                        'action' => 'show401',
                        'params' => array( $this->responseMode )
                    ));
            }
            else
            {
                return $this->response->redirect( 'login' );
            }
        }

        return TRUE;
    }

    public function setStatus( $status = SUCCESS )
    {
        $this->status = $status;
    }

    public function setCode( $code = 200 )
    {
        $this->code = $code;
    }

    public function addMessage( $message, $type = SUCCESS )
    {
        $this->messages[] = array( $type => $message );
    }
}

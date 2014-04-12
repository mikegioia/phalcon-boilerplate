<?php

namespace Controllers;

class ErrorController extends \Base\Controller
{
    public function beforeExecuteRoute()
    {
        $this->checkLoggedIn = FALSE;

        return parent::beforeExecuteRoute();
    }

    public function show404Action( $responseMode = '' )
    {
        /**
         * Alternatively we can set the 404 header:
         *   $this->response->setHeader( 404, 'Not Found' );
         */
        if ( $responseMode )
        {
            $this->responseMode = $responseMode;
        }

        if ( $this->responseMode === 'api' )
        {
            $this->setStatus( ERROR );
            $this->setCode( 404 );
            $this->addMessage( "Page not found", ERROR );
        }
        else
        {
            $this->view->pick( 'errors/404' );
        }
    }

    public function show401Action( $responseMode = '' )
    {
        if ( $responseMode )
        {
            $this->responseMode = $responseMode;
        }

        if ( $this->responseMode === 'api' )
        {
            $this->setStatus( ERROR );
            $this->setCode( 401 );
            $this->addMessage( "Unauthorized access", ERROR );
        }
        else
        {
            $this->view->pick( 'errors/401' );
        }
    }

    public function quitAction( $message = '', $status = SUCCESS, $redirect = NULL, $code = NULL )
    {
        if ( valid( $message, STRING ) )
        {
            $this->addMessage( $message, $status );
        }

        if ( $code )
        {
            $this->code = $code;
        }

        if ( $redirect )
        {
            $this->redirect = $redirect;
        }
    }
}

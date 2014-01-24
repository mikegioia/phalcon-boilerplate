<?php

namespace Lib\Services;

use Phalcon\Validation,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\Email,
    Phalcon\Validation\Validator\StringLength;

/**
 * Validation library
 *
 * @depends service util
 */
class Validate extends \Base\Service
{
    protected $validation;

    /**
     * Add the selected key against the tests. Tests take the form:
     *    type => params
     * where params are the values passed in to the validator.
     */
    public function add( $key, $tests )
    {
        if ( $this->validation === NULL )
        {
            $this->validation = new Validation();
        }

        foreach ( $tests as $test => $params )
        {
            $testObj = NULL;

            switch ( $test )
            {
                case 'exists':
                    $message = sprintf( "%s is required", ucfirst( $key ) );
                    $testObj = new PresenceOf(
                        array(
                            'message' => get( $params, 'message', $message )
                        ));
                    break;

                case 'email':
                    $message = "Please specify a valid email address";
                    $testObj = new Email(
                        array(
                            'message' => get( $params, 'message', $message )
                        ));
                    break;

                case 'length':
                    $message = "Please specify a valid email address";
                    $testObj = new StringLength(
                        array(
                            'message' => get( $params, 'message', $message ),
                            'min' => get( $params, 'min', 0 )
                        ));
                    break;
            }

            if ( ! $testObj )
            {
                throw new \Base\Exception( 'Invalid validation test: '. $test );
            }

            $this->validation
                ->add( $key, $testObj )
                ->setFilters( $key, 'trim' );
        }

        return TRUE;
    }

    public function run( $params )
    {
        if ( is_null( $this->validation ) )
        {
            return FALSE;
        }

        $messages = $this->validation->validate( $params );
        $this->validation = NULL;

        if ( ! count( $messages ) )
        {
            return TRUE;
        }

        // we have errors -- log them and return false
        //
        foreach ( $messages as $message )
        {
            $this->getDI()->getShared( 'util' )
                ->addMessage(
                    $message->getMessage(),
                    ERROR );
        }

        return FALSE;
    }
}

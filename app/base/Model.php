<?php

namespace Base;

use \Phalcon\DI as DI;

class Model extends \Phalcon\Mvc\Model
{
    protected $behaviors = array();

    /**
     * Get the service via static call
     */
    function getService( $service )
    {
        return $this->getDI()->get( $service );
    }

    static function getStaticService( $service )
    {
        return DI::getDefault()->get( $service );
    }

    /**
     * Adds a behavior in the model
     *
     * @param $behavior
     */
    function addBehavior( $behavior )
    {
        $this->behaviors[ $behavior ] = TRUE;
    }

    function beforeSave()
    {
        $di = $this->getDI();

        foreach ( $this->behaviors as $behavior => $active )
        {
            if ( $active && $di->has( $behavior ) )
            {
                $di->get( $behavior )->beforeSave( $this );
            }
        }
    }
}
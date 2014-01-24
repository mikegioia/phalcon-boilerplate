<?php

namespace Base;

use \Phalcon\DI as DI;

class Model extends \Phalcon\Mvc\Model
{
    protected $behaviors = array();

    /**
     * Get the service via static call
     */
    public function getService( $service )
    {
        return $this->getDI()->get( $service );
    }

    /**
     * Adds a behavior in the model
     *
     * @param $behavior
     */
    public function addBehavior( $behavior )
    {
        $this->behaviors[ $behavior ] = TRUE;
    }

    public function beforeSave()
    {
        $di = DI::getDefault();

        foreach ( $this->behaviors as $behavior => $active )
        {
            if ( $active && $di->has( $behavior ) )
            {
                $di->get( $behavior )->beforeSave( $this );
            }
        }
    }
}
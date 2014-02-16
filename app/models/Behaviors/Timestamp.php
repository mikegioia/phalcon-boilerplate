<?php

namespace Db\Behaviors;

use \Phalcon\Mvc\Model\MetaData,
    \Phalcon\Db\Column;

class Timestamp
{
    public function __construct()
    {
    }

    /**
     * beforeSave hook called prior to any Save (insert/update)
    */
    public function beforeSave( $record )
    {
        $timestamp = new \DateTime();
        $datetime = $timestamp->format( DATE_DATABASE );

        if ( property_exists( $record, 'created_at' )
            && empty( $record->created_at ) )
        {
            $record->created_at = $datetime;
        }

        if ( property_exists( $record, 'modified_at' ) )
        {
            $record->modified_at = $datetime;
        }
    }
}
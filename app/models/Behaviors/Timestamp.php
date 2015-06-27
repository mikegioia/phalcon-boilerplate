<?php

namespace Db\Behaviors;

use Phalcon\Db\Column,
    Phalcon\Mvc\Model\Behavior,
    Phalcon\Mvc\Model\MetaData,
    Phalcon\Mvc\ModelInterface,
    Phalcon\Mvc\Model\BehaviorInterface;

class Timestamp extends Behavior implements BehaviorInterface
{
    public function notify( $eventType, ModelInterface $record )
    {
        switch ( $eventType )
        {
            case 'beforeSave':
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

                break;

            default:
        }
    }

    /*
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
    */
}
<?php

namespace Db\Sql;

class Settings extends \Base\Model
{
    public $object_id;
    public $object_type;
    public $key;
    public $value;
    public $created_at;

    function initialize()
    {
        $this->setSource( 'settings' );
    }

    /**
     * Adds a timestamp
     */
    function beforeSave()
    {
        $timestamp = new \DateTime();
        $this->created_at = $timestamp->format( DATE_DATABASE );
    }

    /**
     * Get a setting or settings by key. Object ID and type are required.
     *
     * @param integer $objId
     * @param string $objType
     * @param string $key
     * @param array $options
     * @return string
     */
    static function get( $objId, $objType, $key = NULL, $options = array() )
    {
        $settings = \Db\Sql\Settings::query()
            ->where( 'object_id = :objId:' )
            ->andWhere( 'object_type = :objType:' )
            ->andWhere( 'key = :key:' )
            ->bind(
                array(
                    'objId' => $objId,
                    'objType' => $objType,
                    'key' => $key
                ))
            ->execute();

        return ( get( $options, 'first' ) === TRUE )
            ? $settings->getFirst()
            : $settings;
    }

    /**
     * Return settings by a key/value combination.
     *
     * @param string $key
     * @param string $value
     * @param array $options
     * @return array of Settings
     */
    static function getByKeyValue( $key, $value, $options = array() )
    {
        $settings = \Db\Sql\Settings::query()
            ->where( 'key = :key:' )
            ->andWhere( 'value = :value:' )
            ->bind(
                array(
                    'key' => $key,
                    'value' => $value
                ))
            ->execute();

        return ( get( $options, 'first' ) === TRUE )
            ? $settings->getFirst()
            : $settings;
    }
}

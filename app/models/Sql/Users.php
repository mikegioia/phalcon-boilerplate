<?php

namespace Db\Sql;

class Users extends \Base\Model
{
    function initialize()
    {
        $this->setSource( 'users' );
    }

    /**
     * Load a user by login token, stored as a setting
     */
    static function getByToken( $token )
    {
        $config = self::getStaticService( 'config' );
        $setting = \Db\Sql\Settings::getByKeyValue(
            $config->settings->cookieToken,
            $token->getValue(),
            [ 'first' => TRUE ]);

        if ( ! $setting || ! valid( $setting->object_id ) )
        {
            return FALSE;
        }

        return \Db\Sql\Users::findFirst( $setting->object_id );
    }
}

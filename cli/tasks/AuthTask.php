<?php

namespace Tasks;

class AuthTask extends \Base\Task
{
    function hashAction( $password = "" )
    {
        if ( ! strlen( $password ) )
        {
            throw new \Exception( "Please specify a password to hash." );
        }

        $action = new \Actions\Users\Auth();
        echo $action->hashPassword( $password ) . PHP_EOL;
    }
}
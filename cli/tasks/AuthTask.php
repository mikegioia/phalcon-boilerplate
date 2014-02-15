<?php

namespace Tasks;

class AuthTask extends \Base\Task
{
    function hashAction( $password )
    {
        $action = new \Actions\Users\Auth();
        echo $action->hashPassword( $password ) . PHP_EOL;
    }
}
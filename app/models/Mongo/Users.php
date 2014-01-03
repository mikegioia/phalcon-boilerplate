<?php

namespace Db\Mongo;

class Users extends \Base\Model
{
    function getSource()
    {
        return 'users';
    }
}

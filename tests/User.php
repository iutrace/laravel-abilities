<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Authorizable;

    protected $table = 'users';
}

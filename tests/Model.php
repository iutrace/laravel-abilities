<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Iutrace\Abilities\HasAbilities;
use Iutrace\Abilities\HasPolicies;

class Model extends EloquentModel
{
    use HasAbilities;
    use HasPolicies;

    protected $table = 'models';
}

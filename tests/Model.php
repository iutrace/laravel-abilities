<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Iutrace\Abilities\HasAbilities;

class Model extends EloquentModel
{
    use HasAbilities;

    protected $table = 'models';
}

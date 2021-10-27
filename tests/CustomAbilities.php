<?php

namespace Iutrace\Abilities\Tests;

class CustomAbilities
{
    public function customAllowed(): bool
    {
        return true;
    }

    public function customDenied(): bool
    {
        return false;
    }
}

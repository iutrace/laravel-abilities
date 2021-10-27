<?php

namespace Iutrace\Abilities\Tests;

class ModelPolicy
{
    public function allowedWithoutModel(User $user): bool
    {
        return true;
    }

    public function guestAllowed(User $user = null, Model $model): bool
    {
        return true;
    }

    public function deniedAbilityAllowedGate(User $user, Model $model): bool
    {
        return true;
    }

    public function allowedAbilityDeniedGate(User $user, Model $model): bool
    {
        return false;
    }

    public function allowedAbilityAllowedGate(User $user, Model $model): bool
    {
        return true;
    }

    public function deniedAbilityDeniedGate(User $user, Model $model): bool
    {
        return false;
    }
}

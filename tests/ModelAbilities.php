<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Auth\Access\Response;

class ModelAbilities
{
    public function allowed(Model $model): bool
    {
        return true;
    }

    public function denied(Model $model): bool
    {
        return false;
    }

    public function allowedWithResponse(Model $model): Response
    {
        return Response::allow();
    }

    public function deniedWithResponse(Model $model): Response
    {
        return Response::deny('denied');
    }

    public function allowedAbilityDeniedGate(Model $model): bool
    {
        return true;
    }

    public function deniedAbilityAllowedGate(Model $model): bool
    {
        return false;
    }

    public function allowedAbilityAllowedGate(Model $model): bool
    {
        return true;
    }

    public function deniedAbilityDeniedGate(Model $model): bool
    {
        return false;
    }
}

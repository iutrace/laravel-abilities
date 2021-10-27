<?php

namespace Iutrace\Abilities;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use ReflectionException;
use ReflectionObject;

/**
 * Trait HasAbilities
 * @package Iutrace\Abilities
 * @property array $abilities
 * @property array $policies
 */
trait HasAbilities
{
    public function getAbilitiesAttribute(): array
    {
        /** @var AbilitiesService $abilitiesService */
        $abilitiesService = app(AbilitiesService::class);
        $abilitiesClass = $abilitiesService->resolveAbilitiesClass($this);
        $abilitiesInstance = new $abilitiesClass();

        $abilities = [];
        $reflectionObject = new ReflectionObject($abilitiesInstance);
        foreach ($reflectionObject->getMethods() as $method) {
            $methodName = $method->getName();

            if ($method->isPublic()) {
                /* @var bool|Response $result */
                try {
                    $result = $method->invoke($abilitiesInstance, $this);
                    if (! is_bool($result)) {
                        $result = $result->allowed();
                    }
                    $abilities[Str::snake($methodName)] = $result;
                } catch (ReflectionException $e) {
                }
            }
        }

        return $abilities;
    }

    public function getPoliciesAttribute(): array
    {
        $policies = [];
        $policyClass = Gate::getPolicyFor(get_class($this));
        $policy = new $policyClass();
        $reflectionObject = new ReflectionObject($policy);

        foreach ($reflectionObject->getMethods() as $method) {
            if ($method->isPublic()) {

                $availableParameters = $method->getParameters();
                $parameter = get_class($this);
                if (! isset($availableParameters[2])) { // Less than 3 parameters
                    foreach ($availableParameters as $parameter) {
                        if ($parameter->hasType() && get_class($this) === $parameter->getType()->getName()) {
                            $parameter = $this;
                        }
                    }
                }

                $policies[Str::snake($method->getName())] = Gate::allows(Str::kebab($method->getName()), $parameter);
            }
        }

        return $policies;
    }

    public function can($ability): bool
    {
        /** @var AbilitiesService $abilitiesService */
        $abilitiesService = app(AbilitiesService::class);

        return $abilitiesService->can($ability, $this) ?? false;
    }
}

<?php

namespace Iutrace\Abilities;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;
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
                $result = $method->invoke($abilitiesInstance, $this);
                if (! is_bool($result)) {
                    $result = $result->allowed();
                }
                $abilities[Str::snake($methodName)] = $result;
            }
        }

        return $abilities;
    }

    public function can($ability): bool
    {
        /** @var AbilitiesService $abilitiesService */
        $abilitiesService = app(AbilitiesService::class);

        return $abilitiesService->can($ability, $this) ?? false;
    }
}

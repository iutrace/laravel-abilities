<?php

namespace Iutrace\Abilities;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use ReflectionObject;

trait HasPolicies
{
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
                if ($this->methodHasClassParameter($method, $parameter)) {
                    $parameter = $this;
                }

                $user = auth()->user();

                if (! empty($availableParameters)) { // has parameters
                    if (! $this->parameterAllowGuest($availableParameters[0]) && $user === null) {
                        $policies[Str::snake($method->getName())] = false;
                    } else {
                        $policies[Str::snake($method->getName())] = $method->invokeArgs($policy, [ auth()->user(), $parameter ]);
                    }
                }
            }
        }

        return $policies;
    }

    protected function parameterAllowGuest(\ReflectionParameter $parameter)
    {
        return ($parameter->hasType() && $parameter->allowsNull()) ||
        ($parameter->isDefaultValueAvailable() && is_null($parameter->getDefaultValue()));
    }

    protected function methodHasClassParameter(\ReflectionMethod $method, $class)
    {
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType() && get_class($this) === $parameter->getType()->getName()) {
                return true;
            }
        }

        return false;
    }
}

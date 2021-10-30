<?php

namespace Iutrace\Abilities;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class AbilitiesService
{
    protected $abilities;

    public function __construct(array $abilities)
    {
        $this->abilities = $abilities;
    }

    public function ability($class, $ability): AbilitiesService
    {
        $this->abilities[$class] = $ability;

        return $this;
    }

    public function can($ability, $model): ?bool
    {
        if (! is_object($model)) {
            return null;
        }

        $class = get_class($model);
        $abilitiesClass = $this->resolveAbilitiesClass($class);

        if ($abilitiesClass) {
            $abilitiesInstance = new $abilitiesClass();
            $method = Str::camel($ability);

            if (method_exists($abilitiesInstance, $method)) {
                $result = $abilitiesInstance->$method($model);

                if ($result instanceof Response) {
                    return $result->allowed();
                }

                return $result;
            }
        }

        return null;
    }

    protected function guessAbilitiesClass($class): string
    {
        return $class . 'Abilities';
    }

    public function resolveAbilitiesClass($modelClass): ?string
    {
        $class = $modelClass;
        if (is_object($modelClass)) {
            $class = get_class($modelClass);
        }

        $abilitiesClass = collect($this->abilities)->first(function ($value, $key) use ($class) {
            return $key == $class;
        });

        if (! $abilitiesClass) {
            $abilitiesClass = $this->guessAbilitiesClass($class);
        }

        return class_exists($abilitiesClass) ? $abilitiesClass : null;
    }
}

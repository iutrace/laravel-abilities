<?php

namespace Iutrace\Abilities;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AbilitiesServiceProvider extends ServiceProvider
{
    protected $abilities = [];

    public function register()
    {
        $this->app->singleton(AbilitiesService::class, function () {
            return new AbilitiesService($this->abilities);
        });
    }

    public function boot()
    {
        /* @var AbilitiesService $abilitiesService */
        $abilitiesService = app(AbilitiesService::class);
        Gate::before(function ($user, $ability, $arguments) use ($abilitiesService) {
            if (! empty($arguments)) {
                if ($abilitiesService->can($ability, $arguments[0]) === false) {
                    return false;
                }
            }

            return null;
        });
    }
}

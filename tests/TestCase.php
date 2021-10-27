<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Cookie\CookieServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Iutrace\Abilities\AbilitiesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTest;

class TestCase extends OrchestraTest
{
    protected function getApplicationProviders($app): array
    {
        return [
            FilesystemServiceProvider::class,
            DatabaseServiceProvider::class,
            AuthServiceProvider::class,
            HashServiceProvider::class,
            SessionServiceProvider::class,
            CookieServiceProvider::class,
            AbilitiesServiceProvider::class,
        ];
    }
}

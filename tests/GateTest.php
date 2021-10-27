<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Support\Facades\Gate;

class GateTest extends TestCase
{
    protected $user;
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->model = new Model();

        Gate::policy(Model::class, ModelPolicy::class);
    }

    public function test_allowed_ability_denied_gate()
    {
        $this->assertFalse($this->user->can('allowed-ability-denied-gate', $this->model));
    }

    public function test_denied_ability_allowed_gate()
    {
        $this->assertFalse($this->user->can('denied-ability-allowed-gate', $this->model));
    }

    public function test_allowed_ability_allowed_gate()
    {
        $this->assertTrue($this->user->can('allowed-ability-allowed-gate', $this->model));
    }

    public function test_denied_ability_denied_gate()
    {
        $this->assertFalse($this->user->can('denied-ability-denied-gate', $this->model));
    }

    public function test_interceptor_ability_denied()
    {
        Gate::before(function () {
            return true;
        });
        $this->assertFalse($this->user->can('denied-ability-allowed-gate', $this->model));
    }
}

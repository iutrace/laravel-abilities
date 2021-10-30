<?php

namespace Iutrace\Abilities\Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Iutrace\Abilities\AbilitiesService;

class AbilitiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Gate::policy(Model::class, ModelPolicy::class);
    }

    public function test_can_method()
    {
        $model = new Model();

        $this->assertTrue($model->can('allowed'));
        $this->assertTrue($model->can('allowed-with-response'));
        $this->assertFalse($model->can('denied'));
        $this->assertFalse($model->can('denied-with-response'));
    }

    public function test_abilities_attribute()
    {
        $model = new Model();

        $this->assertEquals($model->abilities, $model->getAbilitiesAttribute());

        $this->assertEquals(true, $model->abilities['allowed']);
        $this->assertEquals(false, $model->abilities['denied']);
        $this->assertEquals(true, $model->abilities['allowed_with_response']);
        $this->assertEquals(false, $model->abilities['denied_with_response']);
    }

    public function test_abilities_attribute_without_abilities_fail()
    {
        $this->expectException(\Exception::class);

        $model = new OtherModel();
        $model->getAbilitiesAttribute();
    }

    public function test_guest_policies_attribute()
    {
        $model = new Model();
        Auth::logout();

        $this->assertEquals($model->policies, $model->getPoliciesAttribute());
        $this->assertTrue($model->policies['guest_allowed']);
        $this->assertFalse($model->policies['allowed_ability_denied_gate']);
        $this->assertFalse($model->policies['denied_ability_allowed_gate']);
        $this->assertFalse($model->policies['allowed_ability_allowed_gate']);
        $this->assertFalse($model->policies['denied_ability_denied_gate']);

        Auth::login(new User());

        $this->assertTrue($model->policies['guest_allowed']);
        $this->assertFalse($model->policies['allowed_ability_denied_gate']);
        $this->assertTrue($model->policies['denied_ability_allowed_gate']);
        $this->assertTrue($model->policies['allowed_ability_allowed_gate']);
        $this->assertFalse($model->policies['denied_ability_denied_gate']);
    }

    public function test_nonexistent_abilities_class()
    {
        $user = new User();

        $this->assertFalse($user->can('allowed', $user));
    }

    public function test_ability_override()
    {
        /* @var AbilitiesService $abilitiesService */
        $abilitiesService = app(AbilitiesService::class);

        Gate::define('custom-allowed', function (User $user, User $user2) {
            return true;
        });
        Gate::define('custom-denied', function (User $user, User $user2) {
            return true;
        });
        $user = new User();

        $this->assertTrue($user->can('custom-allowed', $user));
        $this->assertTrue($user->can('custom-denied', $user));

        $abilitiesService->ability(User::class, CustomAbilities::class);

        $this->assertTrue($user->can('custom-allowed', $user));
        $this->assertFalse($user->can('custom-denied', $user));
    }
}

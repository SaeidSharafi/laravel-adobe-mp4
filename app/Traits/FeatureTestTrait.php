<?php

namespace App\Traits;

use App\Models\Auth\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

trait FeatureTestTrait
{
    protected Authenticatable $user;
    public function unauthorized_user()
    {
        $this->user = User::factory()->create();
        return $this->actingAs($this->user->fresh());
    }

    public function authorized_user(
        array $permission,
        array $allowoverride = ['user']
    ) {
        $this->user = User::factory()->create();
        $role       = Role::updateOrCreate([
            'name'          => 'manager_test',
            'label'         => 'ManagerTest',
            'allowoverride' => Arr::join($allowoverride, ',')
        ]);

        $role->syncPermissions($permission);

        $this->user->assignRole('manager_test');

        return $this->actingAs($this->user->fresh());
    }

    public function student()
    {
        $this->user = User::factory()->create();

        $this->user->assignRole('student');

        return $this->actingAs($this->user->fresh());
    }

    public function admin_user()
    {
        $this->user = User::forceCreate(
            User::factory()->make(['email' => 'admin@lamp4.localhost', 'is_admin' => true])->toArray()
        );

        return $this->actingAs($this->user->fresh());
    }
}

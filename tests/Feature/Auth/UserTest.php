<?php

use App\Models\Auth\User;

/* @var \Tests\TestCase $this */
uses(App\Traits\FeatureTestTrait::class);

it('view all users cannot be rendered to unauthorized user', function () {
    $this->unauthorized_user()
        ->get(route('users.user.index'))
        ->assertForbidden();
});
it('view all users cannot be rendered to authorized user', function () {
    $this->authorized_user(['users.user.view'])
        ->get(route('users.user.index'))
        ->assertOk();
});
it('create user cannot be rendered to unauthorized user', function () {
    $this->unauthorized_user()
        ->get(route('users.user.create'))
        ->assertForbidden();
});
it('create user cannot be rendered to authorized user', function () {
    $this->authorized_user(['users.user.create'])
        ->get(route('users.user.create'))
        ->assertOk();
});
it('unauthorized user can not create user', function () {
    $user                          = User::factory()->make()->toArray();
    $user['roles']                 = ['user'];
    $user['password']              = 'password';
    $user['password_confirmation'] = 'password';

    $this->unauthorized_user()
        ->post(route('users.user.store'), $user)
        ->assertForbidden();
});
it('authorized user can not create user with higher level role', function () {
    $user                          = User::factory()->make()->toArray();
    $user['roles']                 = ['manager'];
    $user['password']              = 'password';
    $user['password_confirmation'] = 'password';

    $this->authorized_user(['users.user.create'])
        ->post(route('users.user.store'), $user)
        ->assertInvalid();

    $this->assertDatabaseMissing(
        'users',
        [
            'email' => $user['email'],
        ]
    );
});
it('authorized user can create user', function () {
    $user                          = User::factory()->make()->toArray();
    $user['roles']                 = ['user'];
    $user['password']              = 'password';
    $user['password_confirmation'] = 'password';

    $this->authorized_user(['users.user.create'])
        ->post(route('users.user.store'), $user);

    $this->assertDatabaseHas(
        'users',
        [
            'email' => $user['email'],
            'phone' => $user['phone'],
        ]
    );
});
it('edit user cannot be rendered to unauthorized user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->unauthorized_user()
        ->get(route('users.user.edit', $user->uuid))
        ->assertForbidden();
});
it('edit user cannot be rendered to authorized user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->authorized_user(['users.user.update'])
        ->get(route('users.user.edit', $user->uuid))
        ->assertOk();
});
it('unauthorized user can not edit user', function () {
    $user = User::factory()->create();

    $user_update = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'phone'      => fake()->unique()->numerify('09#########'),
        'email'      => 'test123@yahoo.com',
        'roles'      => ['user'],
    ];
    $user->assignRole('user');

    $this->unauthorized_user()
        ->put(route('users.user.update', $user->uuid), $user_update)
        ->assertForbidden();
});
it('lower level user can not edit user with higher level role', function () {
    $user      = User::factory()->create();
    $user->assignRole('manager');
    $user_update = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'phone'      => fake()->unique()->numerify('09#########'),
        'email'      => 'test123@yahoo.com',
        'roles'      => ['user'],
    ];

    $this->authorized_user(['users.user.update'])
        ->put(route('users.user.update', $user->uuid), $user_update)
        ->assertForbidden();
});
it('lower level user can not assign user higher level role', function () {
    $user = User::factory()->create();

    $user->assignRole('user');
    $user_update = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'phone'      => fake()->unique()->numerify('09#########'),
        'email'      => 'test123@yahoo.com',
        'roles'      => ['manager'],
    ];
    $this->authorized_user(['users.user.update'])
        ->put(route('users.user.update', $user->uuid), $user_update)
        ->assertSessionHasErrors();

    $this->assertDatabaseMissing(
        'users',
        [
            'email' => 'test123@yahoo.com',
        ]
    );
});
it('authorized user can edit user', function () {
    $user      = User::factory()->create();
    $user->assignRole('user');
    $user_update = [
        'first_name' => fake()->firstName(),
        'last_name'  => fake()->lastName(),
        'phone'      => fake()->unique()->numerify('09#########'),
        'email'      => 'test123@yahoo.com',
        'roles'      => ['user'],
    ];

    $this->authorized_user(['users.user.update'])
        ->put(route('users.user.update', $user->uuid), $user_update)
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id'    => $user->id,
        'email' => 'test123@yahoo.com'
    ]);
});

it('unauthorized user can not delete user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->unauthorized_user()
        ->delete(route('users.user.destroy', $user->uuid))
        ->assertForbidden();
});

it('authorized user can delete user', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->authorized_user(['users.user.delete'])
        ->delete(route('users.user.destroy', $user->uuid));

    $this->assertSoftDeleted(
        'users',
        [
            'id' => $user->id,
        ]
    );
});

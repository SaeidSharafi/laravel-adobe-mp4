<?php

use Spatie\Permission\Models\Role;

it('view all roles cannot be rendered to unauthorized user', function () {
    $this->unauthorized_user()
        ->get(route('users.role.index'))
        ->assertForbidden();
});

it('view all roles can be rendered to authorized user', function () {
    $this->authorized_user(['users.role.view'])
        ->get(route('users.role.index'))
        ->assertOk();
});
it('create user cannot be rendered to unauthorized user', function () {
    $this->unauthorized_user()
        ->get(route('users.role.create'))
        ->assertForbidden();
});

it('create user cannot be rendered to authorized user with only view permission', function () {
    $this->authorized_user(['users.role.view'])
        ->get(route('users.role.create'))
        ->assertForbidden();
});

it('create user can be rendered to authorized user', function () {
    $this->authorized_user(['users.role.view', 'users.role.create'])
        ->get(route('users.role.create'))
        ->assertOk();
});
it('unauthorized user can not create role', function () {
    $role = [
        'name'          => 'levelone',
        'label'         => 'Level 1',
        'allowoverride' => ['user'],
    ];

    $this->unauthorized_user()
        ->post(route('users.role.store'), $role)
        ->assertForbidden();
});
it('authorized user can create role', function () {
    $role = [
        'name'          => 'levelone',
        'label'         => 'Level 1',
        'allowoverride' => ['user'],
    ];

    $this->authorized_user(['users.role.create'])
        ->post(route('users.role.store'), $role)
        ->assertRedirect(route('users.role.index'));

    $this->assertDatabaseHas(
        'roles',
        [
            'name'          => $role['name'],
            'label'         => $role['label'],
            'allowoverride' => 'user',
        ]
    );
});
it('authorized user cannot create role with higher level override', function () {
    $role = [
        'name'          => 'levelone',
        'label'         => 'Level 1',
        'allowoverride' => ['manager'],
    ];

    $this->authorized_user(['users.role.create'])
        ->post(route('users.role.store'), $role)
        ->assertInvalid();

    $this->assertDatabaseMissing(
        'roles',
        [
            'name'          => $role['name'],
            'label'         => $role['label'],
            'allowoverride' => 'manager',
        ]
    );
});
it('edit role cannot be rendered to unauthorized user', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->unauthorized_user()
        ->get(route('users.role.edit', $role->id))
        ->assertForbidden();
});

it('edit role connot be rendered to authorized user wiht only view permission', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->authorized_user(['users.role.view'], ['levelone'])
        ->get(route('users.role.edit', $role->id))
        ->assertForbidden();
});

it('edit role cannot be rendered to authorized user with lower level role', function () {
    $role_high = Role::create(
        [
            'name'          => 'leveltwo',
            'label'         => 'Level 2 Manager',
            'allowoverride' => 'user,levelone',
        ]
    );

    $this->authorized_user(['users.role.view', 'users.role.update'], ['levelone'])
        ->get(route('users.role.edit', $role_high->id))
        ->assertForbidden();
});

it('edit role can be rendered to authorized user', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->authorized_user(['users.role.view', 'users.role.update'], ['levelone'])
        ->get(route('users.role.edit', $role->id))
        ->assertOk();
});
it('unauthorized user can not edit role', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->unauthorized_user()
        ->put(route('users.role.update', $role->id), [
            'name'          => 'leveloneedited',
            'label'         => 'Edited Level 1',
            'allowoverride' => ['levelone'],

        ])
        ->assertForbidden();
});
it('user with lower level role can not edit higher level role', function () {
    $role_high = Role::create(
        [
            'name'          => 'test manager',
            'label'         => 'Test Manager',
            'allowoverride' => 'manager,test',
        ]
    );

    $this->authorized_user(['users.role.update'], ['levelone', 'user'])
        ->put(route('users.role.update', $role_high->id), [
            'name'          => 'roleedited',
            'label'         => 'EDITED Test',
            'allowoverride' => ['user'],
        ])->assertForbidden();
});
it('user with lower level role can not assign higher level role override on edit', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->authorized_user(['users.role.update'], ['levelone'])
        ->put(route('users.role.update', $role->id), [
            'name'          => 'leveloneedited',
            'label'         => 'Edited Level 1',
            'allowoverride' => ['manager'],
        ])->assertInvalid();

    $this->assertDatabaseMissing('roles', [
        'id'    => $role->id,
        'label' => 'Edited Level 1',
    ]);
});
it('authorized user can edit role', function () {
    $role = Role::create([
        'name'          => 'levelone',
        'label'         => 'Level 1',
        'allowoverride' => 'user',
    ]);

    $this->authorized_user(['users.role.update'], ['levelone'])
        ->put(route('users.role.update', $role->id), [
            'name'          => 'roleedited',
            'label'         => 'EDITED Test',
            'allowoverride' => ['levelone'],
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('roles', [
        'id'    => $role->id,
        'label' => 'EDITED Test'
    ]);
    $this->assertDatabaseMissing('roles', [
        'id'   => $role->id,
        'name' => 'roleedited',
    ]);
});
it('unauthorized user can not delete role', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->unauthorized_user()
        ->delete(route('users.role.destroy', $role->id))
        ->assertForbidden();
});
it('authorized user can delete role', function () {
    $role = Role::create(
        [
            'name'          => 'levelone',
            'label'         => 'Level 1',
            'allowoverride' => 'user',
        ]
    );

    $this->authorized_user(['users.role.delete'], ['user', 'levelone'])
        ->delete(route('users.role.destroy', $role->id));

    $this->assertDatabaseMissing(
        'roles',
        [
            'id' => $role->id,
        ]
    );
});

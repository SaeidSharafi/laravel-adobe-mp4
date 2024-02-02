<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('permissions');
        $defaults    = [
            'view',
            'create',
            'update',
            'delete',
        ];
        $defaults_own = [
            'view.own',
            'update.own',
            'delete.own',
        ];

        $formated_permissions = [];
        foreach ($permissions as $section => $sub_permissions) {
            foreach ($sub_permissions as $permission) {
                if ($permission['viewOnly']) {
                    $formated_permissions[] = [
                        'name'       => "{$section}.{$permission['key']}.view",
                        'guard_name' => 'web',
                        'created_at' => now()
                    ];
                    continue;
                }
                foreach ($defaults as $item) {
                    $formated_permissions[] = [
                        'name'       => "{$section}.{$permission['key']}.{$item}",
                        'guard_name' => 'web',
                        'created_at' => now()
                    ];
                }
                if ($permission['hasOwn']) {
                    foreach ($defaults_own as $item) {
                        $formated_permissions[] = [
                            'name'       => "{$section}.{$permission['key']}.{$item}",
                            'guard_name' => 'web',
                            'created_at' => now()
                        ];
                    }
                }
                foreach ($permission['permissions'] as $custom_permission) {
                    $formated_permissions[] = [
                        'name'       => "{$section}.{$permission['key']}.{$custom_permission}",
                        'guard_name' => 'web',
                        'created_at' => now()
                    ];
                }
            }
        }

        Permission::insertOrIgnore($formated_permissions);

        $role = Role::updateOrCreate([
            'name'          => RoleEnum::ADMIN->value,
            'label'         => 'Administrator',
            'allowoverride' => 'admin,manager,user,student',
        ]);
        $role->givePermissionTo(Permission::all());

        $role = Role::updateOrCreate([
            'name'          => RoleEnum::MANAGER->value,
            'label'         => 'Manager',
            'allowoverride' => 'user,student,teacher'
        ]);
        $role->givePermissionTo(Permission::all());

        $role = Role::updateOrCreate([
            'name'   => RoleEnum::USER->value,
            'label'  => 'User',
            'system' => true
        ]);

        $user_permissions = [];
        $role->syncPermissions($user_permissions);
    }
}

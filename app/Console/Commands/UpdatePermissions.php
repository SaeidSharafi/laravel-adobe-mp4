<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UpdatePermissions extends Command
{
    protected $signature = 'permission:update';

    protected $description = 'add new permissions that added to config/permissions.php';

    public function handle()
    {
        if ($this->confirm('Update Permissons?')) {
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
        }
    }
}

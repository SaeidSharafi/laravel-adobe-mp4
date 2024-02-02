<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleService
{
    public static function canOverride(array $allowoverride)
    {
        $roles = Role::query()
            ->select('name')
            ->when( ! auth()->user()->is_admin, function ($roles) {
                $can_override = auth()->user()?->getAllowedOverrides();
                return $roles->whereIn('name', $can_override);
            })
            ->get()
            ->pluck('name')
            ->flatten()
            ->toArray();

        return ! (array_diff($allowoverride, $roles))



        ;
    }

    public static function getFormatedPermissions($allowed_permissions, $role_permissions)
    {
        $all_permission = config('permissions');
        $defaults       = [
            'view',
            'view.own',
            'create',
            'update',
            'update.own',
            'delete',
            'delete.own',
        ];
        $defaults_own = [
            'view.own',
            'update.own',
            'delete.own',
        ];

        $output_permissions = [];
        foreach ($all_permission as $section => $permissions) {
            foreach ($permissions as $permission) {
                $index                                         = $section . "." . $permission['key'];
                $output_permissions[$index]['title']           = $permission['title'];
                $output_permissions[$index]['sub_permissions'] = [];

                foreach ($defaults as $default) {
                    if ($permission['viewOnly'] && ! Str::contains($default, 'view')) {
                        continue;
                    }
                    if ( ! $permission['hasOwn'] && Str::contains($default, 'own')) {
                        continue;
                    }
                    $prm['disabled'] = true;
                    $prm['checked']  = false;
                    $prm['title']    = Str::replace(".", "_", $default);

                    $key = "{$section}.{$permission['key']}.{$default}";
                    if ($allowed_permissions->contains($key)) {
                        $prm['disabled'] = false;
                    }
                    if ($role_permissions->contains($key)) {
                        $prm['checked'] = true;
                    }

                    $output_permissions[$index]['sub_permissions'][$key] = $prm;
                }
            }
        }

        return $output_permissions;
    }

    public static function getEnabledPermissions($role_permissions)
    {
        $all_permission = config('permissions');
        $defaults       = [
            'view',
            'view.own',
            'create',
            'update',
            'update.own',
            'delete',
            'delete.own',
        ];

        $permissions        = [];
        $output_permissions = [];
        foreach ($all_permission as $section => $permissions) {
            foreach ($permissions as $permission) {
                $index = $section . "." . $permission['key'];
                foreach ($defaults as $default) {
                    $key                      = $section . "." . $permission['key'] . "." . $default;
                    $output_permissions[$key] = false;
                    if ($role_permissions->contains($key)) {
                        $output_permissions[$key] = true;
                    }
                }
            }
        }

        return $output_permissions;
    }
}

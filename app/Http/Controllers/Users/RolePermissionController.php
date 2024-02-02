<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\AlertResponseService;
use App\Services\Auth\RoleService;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(Role $role)
    {
        $this->authorize('view', $role);
        $user = auth()->user();
        abort_if( ! $user, 403);

        $allowed_permissions = $user->is_admin
            ? Permission::select(['id', 'name'])->get()->pluck('name', 'id')
            : $user->getAllPermissions()->pluck('name', 'id');

        $role_permissions = $role->getAllPermissions()->pluck('name', 'id');

        $permissions = RoleService::getFormatedPermissions($allowed_permissions, $role_permissions);

        $active_permission = RoleService::getEnabledPermissions($role_permissions);

        $role_info = $role->only(['id', 'name', 'label']);

        return Inertia::render('Permissions/Index', [
            'permissions'       => $permissions,
            'active_permission' => $active_permission,
            'role'              => $role_info
        ]);
    }

    public function update(Role $role)
    {
        $this->authorize('update', $role);

        $permission = collect(request()->get('permissions'))
            ->filter()
            ->keys();

        $allowed_permissions = auth()->user()?->is_admin
            ? Permission::select(['id', 'name'])->get()->pluck('name', 'id')
            : auth()->user()?->getAllPermissions()->pluck('name', 'id')->toArray();

        if ($permission->diff($allowed_permissions)->count() > 0) {
            return redirect()->back()
                ->with('status', AlertResponseService::error("You don't have permission to use selected permissions"));
        }

        $role->syncPermissions($permission);

        return redirect()->route('users.role.index')
            ->withStatus(AlertResponseService::updateSuccess('permission'));
    }
}

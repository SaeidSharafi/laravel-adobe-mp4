<?php

namespace App\Policies\Auth;

use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->can('users.role.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Role  $model
     *
     * @return bool
     */
    public function view(User $user, Role $model)
    {
        if ($user->can('users.role.view')) {
            return in_array($model->name, $user->getAllowedOverrides(), true);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('users.role.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Role  $model
     *
     * @return Response|bool
     */
    public function update(User $user, Role $model)
    {
        if ($user->can('users.role.update')) {
            return in_array($model->name, $user->getAllowedOverrides(), true);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Role  $model
     *
     * @return Response|bool
     */
    public function delete(User $user, Role $model)
    {
        if ($user->can('users.role.delete')) {
            return in_array($model->name, $user->getAllowedOverrides(), true);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Role  $model
     *
     * @return Response|bool
     */
    public function restore(User $user, Role $model)
    {
        return $user->can('users.role.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Role  $model
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Role $model)
    {
        return $user->can('users.role.force_delete');
    }
}

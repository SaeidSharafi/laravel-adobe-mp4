<?php

namespace App\Policies\Auth;

use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return $user->can('users.user.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function view(User $user, User $model)
    {
        if ($user->id !== $model->id) {
            if ($user->can('users.user.view')) {
                return $model->hasAnyRole($user->getAllowedOverrides());
            }

            return false;
        }
        return $user->can('users.user.view.own');
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
        return $user->can('users.user.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function update(User $user, User $model)
    {
        if ($user->can('users.user.update')) {
            if ($model->id === $user->id) {
                return false;
            }
            return $model->hasAnyRole($user->getAllowedOverrides());
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function updateOwn(User $user, User $model)
    {
        if ($user->id !== $model->id) {
            return false;
        }

        return $user->can('users.user.update.own');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function delete(User $user, User $model)
    {
        if ($user->id !== $model->id) {
            if ($user->can('users.user.delete')) {
                return $model->hasAnyRole($user->getAllowedOverrides());
            }

            return false;
        }

        return $user->can('users.user.delete.own');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function restore(User $user, User $model)
    {
        return $user->can('users.user.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  User  $model
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return $user->can('users.user.force_delete');
    }
}

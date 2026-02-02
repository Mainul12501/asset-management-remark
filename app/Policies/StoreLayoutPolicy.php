<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StoreLayout;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreLayoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the storeLayout can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the storeLayout can view the model.
     */
    public function view(User $user, StoreLayout $model): bool
    {
        return true;
    }

    /**
     * Determine whether the storeLayout can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the storeLayout can update the model.
     */
    public function update(User $user, StoreLayout $model): bool
    {
        return true;
    }

    /**
     * Determine whether the storeLayout can delete the model.
     */
    public function delete(User $user, StoreLayout $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the storeLayout can restore the model.
     */
    public function restore(User $user, StoreLayout $model): bool
    {
        return false;
    }

    /**
     * Determine whether the storeLayout can permanently delete the model.
     */
    public function forceDelete(User $user, StoreLayout $model): bool
    {
        return false;
    }
}

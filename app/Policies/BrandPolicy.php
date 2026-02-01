<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the brand can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the brand can view the model.
     */
    public function view(User $user, Brand $model): bool
    {
        return true;
    }

    /**
     * Determine whether the brand can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the brand can update the model.
     */
    public function update(User $user, Brand $model): bool
    {
        return true;
    }

    /**
     * Determine whether the brand can delete the model.
     */
    public function delete(User $user, Brand $model): bool
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
     * Determine whether the brand can restore the model.
     */
    public function restore(User $user, Brand $model): bool
    {
        return false;
    }

    /**
     * Determine whether the brand can permanently delete the model.
     */
    public function forceDelete(User $user, Brand $model): bool
    {
        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the category can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the category can view the model.
     */
    public function view(User $user, Category $model): bool
    {
        return true;
    }

    /**
     * Determine whether the category can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the category can update the model.
     */
    public function update(User $user, Category $model): bool
    {
        return true;
    }

    /**
     * Determine whether the category can delete the model.
     */
    public function delete(User $user, Category $model): bool
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
     * Determine whether the category can restore the model.
     */
    public function restore(User $user, Category $model): bool
    {
        return false;
    }

    /**
     * Determine whether the category can permanently delete the model.
     */
    public function forceDelete(User $user, Category $model): bool
    {
        return false;
    }
}

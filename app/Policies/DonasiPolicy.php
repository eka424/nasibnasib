<?php

namespace App\Policies;

use App\Models\Donasi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonasiPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Donasi $donasi): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Donasi $donasi): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Donasi $donasi): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Donasi $donasi): bool
    {
        return $this->delete($user, $donasi);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Donasi $donasi): bool
    {
        return $this->delete($user, $donasi);
    }
}

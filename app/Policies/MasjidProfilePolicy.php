<?php

namespace App\Policies;

use App\Models\MasjidProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MasjidProfilePolicy
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
    public function view(User $user, MasjidProfile $masjidProfile): bool
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
    public function update(User $user, MasjidProfile $masjidProfile): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MasjidProfile $masjidProfile): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MasjidProfile $masjidProfile): bool
    {
        return $this->delete($user, $masjidProfile);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MasjidProfile $masjidProfile): bool
    {
        return $this->delete($user, $masjidProfile);
    }
}

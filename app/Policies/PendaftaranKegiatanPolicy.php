<?php

namespace App\Policies;

use App\Models\PendaftaranKegiatan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PendaftaranKegiatanPolicy
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
    public function view(User $user, PendaftaranKegiatan $pendaftaranKegiatan): bool
    {
        return $user->isAdminOrPengurus() || $pendaftaranKegiatan->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdminOrPengurus() || $user->isJamaah();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PendaftaranKegiatan $pendaftaranKegiatan): bool
    {
        return $user->isAdminOrPengurus();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PendaftaranKegiatan $pendaftaranKegiatan): bool
    {
        return $user->isAdminOrPengurus() || $pendaftaranKegiatan->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PendaftaranKegiatan $pendaftaranKegiatan): bool
    {
        return $this->delete($user, $pendaftaranKegiatan);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PendaftaranKegiatan $pendaftaranKegiatan): bool
    {
        return $this->delete($user, $pendaftaranKegiatan);
    }
}

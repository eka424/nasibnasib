<?php

namespace App\Policies;

use App\Models\PertanyaanUstadz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PertanyaanUstadzPolicy
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
        return $user->isAdminOrPengurus() || $user->isUstadz();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PertanyaanUstadz $pertanyaanUstadz): bool
    {
        return $user->isAdminOrPengurus()
            || ($user->isUstadz() && $pertanyaanUstadz->ustadz_id === $user->id)
            || $pertanyaanUstadz->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PertanyaanUstadz $pertanyaanUstadz): bool
    {
        if ($user->isAdminOrPengurus()) {
            return true;
        }

        if ($user->isUstadz()) {
            return $pertanyaanUstadz->ustadz_id === $user->id;
        }

        return $pertanyaanUstadz->user_id === $user->id && $pertanyaanUstadz->status === 'menunggu';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PertanyaanUstadz $pertanyaanUstadz): bool
    {
        if ($user->isAdminOrPengurus()) {
            return true;
        }

        return $pertanyaanUstadz->user_id === $user->id && $pertanyaanUstadz->status === 'menunggu';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PertanyaanUstadz $pertanyaanUstadz): bool
    {
        return $this->delete($user, $pertanyaanUstadz);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PertanyaanUstadz $pertanyaanUstadz): bool
    {
        return $this->delete($user, $pertanyaanUstadz);
    }
}

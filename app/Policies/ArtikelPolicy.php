<?php

namespace App\Policies;

use App\Models\Artikel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtikelPolicy
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
        return $user->isPengurus();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Artikel $artikel): bool
    {
        return $user->isPengurus() || $artikel->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPengurus();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Artikel $artikel): bool
    {
        return $user->isPengurus() || $artikel->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Artikel $artikel): bool
    {
        return $user->isPengurus() || $artikel->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Artikel $artikel): bool
    {
        return $this->delete($user, $artikel);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Artikel $artikel): bool
    {
        return $this->delete($user, $artikel);
    }
}

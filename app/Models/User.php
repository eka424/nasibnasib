<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'address',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function hasRole(array|string $roles): bool
    {
        return in_array($this->role, (array) $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPengurus(): bool
    {
        return $this->role === 'pengurus';
    }

    public function isUstadz(): bool
    {
        return $this->role === 'ustadz';
    }

    public function isJamaah(): bool
    {
        return $this->role === 'jamaah';
    }

    public function isAdminOrPengurus(): bool
    {
        return $this->hasRole(['admin', 'pengurus']);
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }

    public function pendaftaranKegiatans(): HasMany
    {
        return $this->hasMany(PendaftaranKegiatan::class);
    }

    public function transaksiDonasis(): HasMany
    {
        return $this->hasMany(TransaksiDonasi::class);
    }

    public function pertanyaanDiajukan(): HasMany
    {
        return $this->hasMany(PertanyaanUstadz::class, 'user_id');
    }

    public function pertanyaanDitugaskan(): HasMany
    {
        return $this->hasMany(PertanyaanUstadz::class, 'ustadz_id');
    }
}

<?php

namespace App\Providers;

use App\Models\Artikel;
use App\Models\Donasi;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\MasjidProfile;
use App\Models\PendaftaranKegiatan;
use App\Models\Perpustakaan;
use App\Models\PertanyaanUstadz;
use App\Models\TransaksiDonasi;
use App\Policies\ArtikelPolicy;
use App\Policies\DonasiPolicy;
use App\Policies\GaleriPolicy;
use App\Policies\KegiatanPolicy;
use App\Policies\MasjidProfilePolicy;
use App\Policies\PendaftaranKegiatanPolicy;
use App\Policies\PerpustakaanPolicy;
use App\Policies\PertanyaanUstadzPolicy;
use App\Policies\TransaksiDonasiPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Artikel::class => ArtikelPolicy::class,
        Kegiatan::class => KegiatanPolicy::class,
        Donasi::class => DonasiPolicy::class,
        Galeri::class => GaleriPolicy::class,
        Perpustakaan::class => PerpustakaanPolicy::class,
        MasjidProfile::class => MasjidProfilePolicy::class,
        PendaftaranKegiatan::class => PendaftaranKegiatanPolicy::class,
        TransaksiDonasi::class => TransaksiDonasiPolicy::class,
        PertanyaanUstadz::class => PertanyaanUstadzPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-admin', fn (User $user) => $user->isAdmin());
        Gate::define('access-backoffice', fn (User $user) => $user->isAdminOrPengurus());
        Gate::define('access-ustadz', fn (User $user) => $user->isUstadz());
        Gate::define('access-jamaah', fn (User $user) => $user->isJamaah());
    }
}

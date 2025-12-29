<?php

use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonasiController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\ModerasiTanyaUstadzController;
use App\Http\Controllers\Admin\PendaftaranKegiatanController;
use App\Http\Controllers\Admin\PerpustakaanController;
use App\Http\Controllers\Admin\ProfilMasjidController;
use App\Http\Controllers\Admin\TransaksiDonasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\ArtikelFrontController;
use App\Http\Controllers\Frontend\DonasiFrontController;
use App\Http\Controllers\Frontend\GaleriFrontController;
use App\Http\Controllers\Frontend\KegiatanFrontController;
use App\Http\Controllers\Frontend\PerpustakaanFrontController;
use App\Http\Controllers\Frontend\TanyaUstadzFrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengurus\ArtikelController as PengurusArtikelController;
use App\Http\Controllers\Pengurus\KegiatanController as PengurusKegiatanController;
use App\Http\Controllers\Pengurus\PerpustakaanController as PengurusPerpustakaanController;
use App\Http\Controllers\Ustadz\PertanyaanController as UstadzPertanyaanController;
use App\Http\Controllers\Webhook\XenditWebhookController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MosqueProfileController;
use App\Http\Controllers\Admin\MosqueProfileAdminController;
use App\Http\Controllers\MosqueStructureController;
use App\Http\Controllers\Admin\MosqueStructureAdminController;
use App\Http\Controllers\MosqueWorkProgramController;
use App\Http\Controllers\Admin\WorkProgramAdminController;

Route::get('/', [ArtikelFrontController::class, 'home'])->name('home');

Route::get('/artikel', [ArtikelFrontController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{artikel:slug}', [ArtikelFrontController::class, 'show'])->name('artikel.show');

Route::get('/kegiatan', [KegiatanFrontController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{kegiatan}', [KegiatanFrontController::class, 'show'])->name('kegiatan.show');

Route::get('/donasi', [DonasiFrontController::class, 'index'])->name('donasi.index');
Route::get('/donasi/{donasi}', [DonasiFrontController::class, 'show'])->name('donasi.show');

Route::get('/galeri', [GaleriFrontController::class, 'index'])->name('galeri.index');
Route::get('/perpustakaan', [PerpustakaanFrontController::class, 'index'])->name('perpustakaan.index');
Route::get('/perpustakaan/{perpustakaan}', [PerpustakaanFrontController::class, 'show'])->name('perpustakaan.show');

Route::get('/tanya-ustadz', [TanyaUstadzFrontController::class, 'index'])->name('tanya-ustadz.index');

Route::post('/webhook/xendit', XenditWebhookController::class)
    ->name('webhook.xendit')
    ->withoutMiddleware([ValidateCsrfToken::class]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->role === 'pengurus') {
            return redirect()->route('admin.pengurus.dashboard');
        }

        if ($user?->role === 'ustadz') {
            return redirect()->route('ustadz.pertanyaan.index');
        }

        return view('dashboard');
    })->name('dashboard');

    Route::post('/kegiatan/{kegiatan}/daftar', [KegiatanFrontController::class, 'daftar'])->name('kegiatan.daftar');
    Route::get('/kegiatan/riwayat', [KegiatanFrontController::class, 'riwayat'])->name('kegiatan.riwayat');

    Route::post('/donasi/{donasi}/transaksi', [DonasiFrontController::class, 'donate'])->name('donasi.transaksi');
    Route::get('/donasi/riwayat', [DonasiFrontController::class, 'riwayat'])->name('donasi.riwayat');

    Route::post('/tanya-ustadz', [TanyaUstadzFrontController::class, 'store'])->name('tanya-ustadz.store');
    Route::get('/tanya-ustadz-saya', [TanyaUstadzFrontController::class, 'my'])->name('tanya-ustadz.my');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('users', UserController::class);
        });

        Route::middleware(['role:admin'])->group(function () {
            Route::get('/', fn () => redirect()->route('admin.dashboard'));
            Route::get('dashboard', DashboardController::class)->name('dashboard');
            Route::get('profil-masjid', [ProfilMasjidController::class, 'edit'])->name('profil.edit');
            Route::put('profil-masjid', [ProfilMasjidController::class, 'update'])->name('profil.update');

            Route::resource('pendaftaran-kegiatans', PendaftaranKegiatanController::class)
                ->parameters(['pendaftaran-kegiatans' => 'pendaftaran_kegiatan']);

            Route::resource('donasis', DonasiController::class);
            Route::post('donasis/{donasi}/recalc', [DonasiController::class, 'recalc'])->name('donasis.recalc');

            Route::resource('transaksi-donasis', TransaksiDonasiController::class)
                ->parameters(['transaksi-donasis' => 'transaksi_donasi']);

            Route::resource('galeris', GaleriController::class);

            Route::get('moderasi-pertanyaan', [ModerasiTanyaUstadzController::class, 'index'])->name('moderasi.index');
            Route::post('moderasi-pertanyaan/{pertanyaan}/assign', [ModerasiTanyaUstadzController::class, 'assign'])->name('moderasi.assign');
            Route::delete('moderasi-pertanyaan/{pertanyaan}', [ModerasiTanyaUstadzController::class, 'destroy'])->name('moderasi.destroy');
        });

        Route::middleware(['role:admin|pengurus'])->group(function () {
            Route::resource('artikels', ArtikelController::class);
            Route::resource('kegiatans', KegiatanController::class);
            Route::resource('perpustakaans', PerpustakaanController::class);
        });
    });

Route::prefix('ustadz')
    ->name('ustadz.')
    ->middleware(['auth', 'verified', 'role:ustadz'])
    ->group(function () {
        Route::get('pertanyaan', [UstadzPertanyaanController::class, 'index'])->name('pertanyaan.index');
        Route::get('pertanyaan/riwayat', [UstadzPertanyaanController::class, 'riwayat'])->name('pertanyaan.riwayat');
        Route::get('pertanyaan/{pertanyaan}/edit', [UstadzPertanyaanController::class, 'edit'])->name('pertanyaan.edit');
        Route::put('pertanyaan/{pertanyaan}', [UstadzPertanyaanController::class, 'update'])->name('pertanyaan.update');
    });

Route::prefix('pengurus')
    ->name('admin.pengurus.')
    ->middleware(['auth', 'verified', 'role:pengurus'])
    ->group(function () {
        Route::get('/', [PengurusArtikelController::class, 'index'])->name('artikel');
        Route::get('kegiatan', [PengurusKegiatanController::class, 'index'])->name('kegiatan');
        Route::get('perpustakaan', [PengurusPerpustakaanController::class, 'index'])->name('perpustakaan');
        Route::get('dashboard', fn () => view('admin.pengurus-dashboard'))->name('dashboard');
    });

require __DIR__.'/auth.php';

/** Profil Masjid (Publik) */
Route::get('/profil-masjid', [MosqueProfileController::class, 'show'])->name('mosque.profile');
Route::get('/profil-masjid/sejarah', [MosqueProfileController::class, 'sejarah'])->name('mosque.sejarah');

/** Admin edit profil masjid (yang kamu pakai: admin.mosque_profile.*) */
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profil-masjid/edit', [MosqueProfileAdminController::class, 'edit'])->name('mosque_profile.edit');
    Route::put('/profil-masjid', [MosqueProfileAdminController::class, 'update'])->name('mosque_profile.update');
});

/**struktur */

// publik
Route::get('/profil-masjid/struktur', [MosqueStructureController::class, 'index'])->name('mosque.struktur');

// admin (pakai middleware admin yang sudah kamu punya)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/struktur-masjid', [MosqueStructureAdminController::class, 'index'])->name('mosque_structure.index');
    Route::post('/struktur-masjid', [MosqueStructureAdminController::class, 'store'])->name('mosque_structure.store');
    Route::put('/struktur-masjid/{node}', [MosqueStructureAdminController::class, 'update'])->name('mosque_structure.update');
    Route::delete('/struktur-masjid/{node}', [MosqueStructureAdminController::class, 'destroy'])->name('mosque_structure.destroy');
});

Route::get('/profil-masjid/program-kerja', [MosqueWorkProgramController::class, 'index'])
  ->name('mosque.work_program');

  Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/program-kerja', [WorkProgramAdminController::class, 'index'])->name('work_program.all');

    // seksi
    Route::post('/program-kerja/section', [WorkProgramAdminController::class, 'storeSection'])->name('work_program.section.store');
    Route::put('/program-kerja/section/{section}', [WorkProgramAdminController::class, 'updateSection'])->name('work_program.section.update');
    Route::delete('/program-kerja/section/{section}', [WorkProgramAdminController::class, 'destroySection'])->name('work_program.section.destroy');

    // bagian
    Route::post('/program-kerja/part', [WorkProgramAdminController::class, 'storePart'])->name('work_program.part.store');
    Route::put('/program-kerja/part/{part}', [WorkProgramAdminController::class, 'updatePart'])->name('work_program.part.update');
    Route::delete('/program-kerja/part/{part}', [WorkProgramAdminController::class, 'destroyPart'])->name('work_program.part.destroy');

    // item
    Route::post('/program-kerja/item', [WorkProgramAdminController::class, 'storeItem'])->name('work_program.item.store');
    Route::put('/program-kerja/item/{item}', [WorkProgramAdminController::class, 'updateItem'])->name('work_program.item.update');
    Route::delete('/program-kerja/item/{item}', [WorkProgramAdminController::class, 'destroyItem'])->name('work_program.item.destroy');
});
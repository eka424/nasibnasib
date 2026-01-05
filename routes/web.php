<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

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

// (kalau memang ada, pastikan file controllernya ada)
use App\Http\Controllers\Frontend\QuranFrontController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengurus\ArtikelController as PengurusArtikelController;
use App\Http\Controllers\Pengurus\KegiatanController as PengurusKegiatanController;
use App\Http\Controllers\Pengurus\PerpustakaanController as PengurusPerpustakaanController;

use App\Http\Controllers\Ustadz\PertanyaanController as UstadzPertanyaanController;
use App\Http\Controllers\Webhook\XenditWebhookController;

use App\Http\Controllers\MosqueProfileController;
use App\Http\Controllers\MosqueStructureController;
use App\Http\Controllers\Admin\MosqueStructureAdminController;
use App\Http\Controllers\MosqueWorkProgramController;
use App\Http\Controllers\Admin\WorkProgramAdminController;
use App\Http\Controllers\Frontend\PerpustakaanIntegratedController;

// KIDS
use App\Http\Controllers\KidsLibraryController;
use App\Http\Controllers\Admin\KidsContentController;


use App\Http\Controllers\SedekahMasjidController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\SedekahAdminController;
use App\Http\Controllers\Frontend\StructureController;

use App\Http\Controllers\Admin\ManagementTermController;
use App\Http\Controllers\Admin\ManagementBuilderController;

use App\Http\Controllers\Admin\ManagementCrudController;

use App\Http\Controllers\PublicFinanceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\YearBalanceController;

/*
|--------------------------------------------------------------------------
| FRONT
|--------------------------------------------------------------------------
*/
Route::get('/', [ArtikelFrontController::class, 'home'])->name('home');

Route::get('/artikel', [ArtikelFrontController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{artikel:slug}', [ArtikelFrontController::class, 'show'])->name('artikel.show');

// ===============================
// GALERI (FRONT / JAMAAH)
// ===============================
Route::get('/galeri', [GaleriFrontController::class, 'index'])->name('galeri.index');
Route::get('/galeri/{galeri}', [GaleriFrontController::class, 'show'])->name('galeri.show');


// ===============================
// KEGIATAN
// ===============================
Route::get('/kegiatan', [KegiatanFrontController::class, 'index'])->name('kegiatan.index');

// publik
Route::get('/kegiatan/kalender', [KegiatanFrontController::class, 'calendar'])
    ->name('kegiatan.calendar');

// user area (auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kegiatan/riwayat', [KegiatanFrontController::class, 'riwayat'])
        ->name('kegiatan.riwayat');

    Route::post('/kegiatan/{kegiatan}/daftar', [KegiatanFrontController::class, 'daftar'])
        ->name('kegiatan.daftar');
});

// dinamis (PALING BAWAH)
Route::get('/kegiatan/{kegiatan}', [KegiatanFrontController::class, 'show'])
    ->name('kegiatan.show');

/*
|-------------------------------------------------------------------------- 
| PERPUSTAKAAN (GABUNGAN + RAMAH ANAK + HADITS)
|-------------------------------------------------------------------------- 
| IMPORTANT:
| - Route spesifik /perpustakaan/... HARUS di atas route dinamis /perpustakaan/{perpustakaan}
*/

Route::get('/perpustakaan', [\App\Http\Controllers\Frontend\PerpustakaanIntegratedController::class, 'index'])
    ->name('perpustakaan.index');

// ✅ Ramah Anak (Kids Library)
Route::get('/perpustakaan/ramah-anak', [\App\Http\Controllers\KidsLibraryController::class, 'index'])
    ->name('perpustakaan.ramah-anak');

/*
|-------------------------------------------------------------------------- 
| HADITS (Flow lengkap)
|-------------------------------------------------------------------------- 
| Klik Kitab -> List nomor -> Detail Hadits
| URL:
| - /perpustakaan/hadits
| - /perpustakaan/hadits/bukhari
| - /perpustakaan/hadits/bukhari/1
*/

// daftar kitab hadits
Route::get('/perpustakaan/hadits', [\App\Http\Controllers\Frontend\HadithFrontController::class, 'index'])
    ->name('perpustakaan.hadits.index');

// list nomor hadits (per kitab)
Route::get('/perpustakaan/hadits/{slug}', [\App\Http\Controllers\Frontend\HadithFrontController::class, 'list'])
    ->name('hadits.list');

// detail hadits (per nomor)
Route::get('/perpustakaan/hadits/{slug}/{number}', [\App\Http\Controllers\Frontend\HadithFrontController::class, 'detail'])
    ->whereNumber('number')
    ->name('hadits.detail');

/*
|-------------------------------------------------------------------------- 
| Perpustakaan detail buku (route dinamis) - HARUS PALING BAWAH
|-------------------------------------------------------------------------- 
*/
Route::get('/perpustakaan/{perpustakaan}', [\App\Http\Controllers\Frontend\PerpustakaanFrontController::class, 'show'])
    ->name('perpustakaan.show');

/*
|--------------------------------------------------------------------------
| ALQURAN
|--------------------------------------------------------------------------
*/
Route::get('/alquran', [QuranFrontController::class, 'index'])->name('quran.index');
Route::get('/alquran/surah/{surah}', [QuranFrontController::class, 'show'])->whereNumber('surah')->name('quran.show');

Route::get('/tanya-ustadz', [TanyaUstadzFrontController::class, 'index'])->name('tanya-ustadz.index');

/*
|--------------------------------------------------------------------------
| WEBHOOK
|--------------------------------------------------------------------------
*/
Route::post('/webhook/xendit', XenditWebhookController::class)
    ->name('webhook.xendit')
    ->withoutMiddleware([ValidateCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| AUTH USER AREA
|--------------------------------------------------------------------------
*/
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

    
   

    Route::post('/donasi/{donasi}/transaksi', [DonasiFrontController::class, 'donate'])->name('donasi.transaksi');
    Route::get('/donasi/riwayat', [DonasiFrontController::class, 'riwayat'])->name('donasi.riwayat');

    Route::post('/tanya-ustadz', [TanyaUstadzFrontController::class, 'store'])->name('tanya-ustadz.store');
    Route::get('/tanya-ustadz-saya', [TanyaUstadzFrontController::class, 'my'])->name('tanya-ustadz.my');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
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

            // ✅ ADMIN: Kids Content (Ramah Anak) — masuk dari /admin/ramah-anak
            Route::get('/ramah-anak', [KidsContentController::class, 'index'])->name('kids.index');
            Route::get('/ramah-anak/create', [KidsContentController::class, 'create'])->name('kids.create');
            Route::post('/ramah-anak', [KidsContentController::class, 'store'])->name('kids.store');
            Route::get('/ramah-anak/{kids}/edit', [KidsContentController::class, 'edit'])->name('kids.edit');
            Route::put('/ramah-anak/{kids}', [KidsContentController::class, 'update'])->name('kids.update');
            Route::delete('/ramah-anak/{kids}', [KidsContentController::class, 'destroy'])->name('kids.destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| USTADZ
|--------------------------------------------------------------------------
*/
Route::prefix('ustadz')
    ->name('ustadz.')
    ->middleware(['auth', 'verified', 'role:ustadz'])
    ->group(function () {
        Route::get('pertanyaan', [UstadzPertanyaanController::class, 'index'])->name('pertanyaan.index');
        Route::get('pertanyaan/riwayat', [UstadzPertanyaanController::class, 'riwayat'])->name('pertanyaan.riwayat');
        Route::get('pertanyaan/{pertanyaan}/edit', [UstadzPertanyaanController::class, 'edit'])->name('pertanyaan.edit');
        Route::put('pertanyaan/{pertanyaan}', [UstadzPertanyaanController::class, 'update'])->name('pertanyaan.update');
    });

/*
|--------------------------------------------------------------------------
| PENGURUS
|--------------------------------------------------------------------------
*/
Route::prefix('pengurus')
    ->name('admin.pengurus.')
    ->middleware(['auth', 'verified', 'role:pengurus'])
    ->group(function () {
        Route::get('/', [PengurusArtikelController::class, 'index'])->name('artikel');
        Route::get('kegiatan', [PengurusKegiatanController::class, 'index'])->name('kegiatan');
        Route::get('perpustakaan', [PengurusPerpustakaanController::class, 'index'])->name('perpustakaan');
        Route::get('dashboard', fn () => view('admin.pengurus-dashboard'))->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| PROFIL MASJID (PUBLIK)
|--------------------------------------------------------------------------
*/
Route::get('/profil-masjid', [MosqueProfileController::class, 'show'])->name('mosque.profile');
Route::get('/profil-masjid/sejarah', [MosqueProfileController::class, 'sejarah'])->name('mosque.sejarah');


// publik

// admin (pakai middleware admin yang sudah kamu punya)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/struktur-masjid', [MosqueStructureAdminController::class, 'index'])->name('mosque_structure.index');
    Route::post('/struktur-masjid', [MosqueStructureAdminController::class, 'store'])->name('mosque_structure.store');
    Route::put('/struktur-masjid/{node}', [MosqueStructureAdminController::class, 'update'])->name('mosque_structure.update');
    Route::delete('/struktur-masjid/{node}', [MosqueStructureAdminController::class, 'destroy'])->name('mosque_structure.destroy');
});

/*
|--------------------------------------------------------------------------
| PROGRAM KERJA
|--------------------------------------------------------------------------
*/
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


// FRONT
Route::get('/sedekah-masjid', [SedekahMasjidController::class, 'index'])->name('sedekah.index');
Route::post('/sedekah-masjid/transaksi', [SedekahMasjidController::class, 'createTransaction'])->name('sedekah.transaksi');

Route::get('/sedekah-masjid/pay/{order_id}', [SedekahMasjidController::class, 'payPage'])->name('sedekah.pay');
Route::get('/sedekah-masjid/finish/{order_id}', [SedekahMasjidController::class, 'finish'])->name('sedekah.finish');

Route::middleware('auth')->get('/sedekah-masjid/riwayat', [SedekahMasjidController::class, 'riwayat'])->name('sedekah.riwayat');

// MIDTRANS WEBHOOK (set di dashboard Midtrans -> Notification URL)
Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');

// ADMIN
Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
  Route::get('/sedekah', [SedekahAdminController::class, 'dashboard'])->name('admin.sedekah.dashboard');

  // ✅ TAMBAH INI
  Route::get('/sedekah/transactions', [SedekahAdminController::class, 'transactions'])->name('admin.sedekah.transactions');

  Route::get('/sedekah/campaigns', [SedekahAdminController::class, 'campaigns'])->name('admin.sedekah.campaigns');
  Route::post('/sedekah/campaigns', [SedekahAdminController::class, 'campaignStore'])->name('admin.sedekah.campaigns.store');
  Route::put('/sedekah/campaigns/{campaign}', [SedekahAdminController::class, 'campaignUpdate'])->name('admin.sedekah.campaigns.update');
});
Route::get('/profil-masjid/struktur', [MosqueStructureController::class, 'index'])->name('mosque.struktur');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {



    // aksi cepat
    Route::post('/struktur/{term}/set-active', [ManagementTermController::class, 'setActive'])->name('admin.struktur.setActive');
    Route::post('/struktur/{term}/publish', [ManagementTermController::class, 'publish'])->name('admin.struktur.publish');
});
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::middleware(['role:admin|pengurus'])->group(function () {

            // ===============================
            // STRUKTUR PENGURUS
            // ===============================
            Route::get('/struktur', [ManagementTermController::class, 'index'])
                ->name('struktur.index');

            Route::get('/struktur/create', [ManagementTermController::class, 'create'])
                ->name('struktur.create');

            Route::post('/struktur', [ManagementTermController::class, 'store'])
                ->name('struktur.store');

            Route::get('/struktur/{term}/edit', [ManagementTermController::class, 'edit'])
                ->name('struktur.edit');

            Route::put('/struktur/{term}', [ManagementTermController::class, 'update'])
                ->name('struktur.update');

            // ✅ INI YANG TADI HILANG
            Route::get('/struktur/{term}/builder', [ManagementBuilderController::class, 'builder'])
                ->name('struktur.builder');

            // aksi cepat
            Route::post('/struktur/{term}/set-active', [ManagementTermController::class, 'setActive'])
                ->name('struktur.setActive');

            Route::post('/struktur/{term}/publish', [ManagementTermController::class, 'publish'])
                ->name('struktur.publish');

            // CRUD ajax
            Route::post('/units', [ManagementCrudController::class, 'storeUnit'])
                ->name('units.store');

            Route::post('/positions', [ManagementCrudController::class, 'storePosition'])
                ->name('positions.store');

            Route::post('/members', [ManagementCrudController::class, 'storeMember'])
                ->name('members.store');
        });
        Route::get('/profil-masjid/struktur', [MosqueStructureController::class, 'index'])
    ->name('mosque.struktur');

    });

// ===== PUBLIK KEUANGAN =====
Route::get('/keuangan', [PublicFinanceController::class, 'index'])->name('public.finance');
Route::get('/keuangan/bukti/{transaction}', [PublicFinanceController::class, 'receipt'])->name('public.finance.receipt');
Route::get('/keuangan/rekap-tahunan', [PublicFinanceController::class, 'yearly'])->name('public.finance.yearly');

// ===== ADMIN KEUANGAN =====
Route::middleware(['auth'])->prefix('admin/keuangan')->name('admin.finance.')->group(function () {
    Route::resource('transaksi', TransactionController::class)->except(['show']);

    // Saldo Awal / Sisa Kas per Tahun
    Route::get('/saldo-awal', [YearBalanceController::class, 'index'])->name('year-balance.index');
    Route::post('/saldo-awal', [YearBalanceController::class, 'store'])->name('year-balance.store');
});

require __DIR__.'/auth.php';

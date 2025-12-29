<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mosque_profiles', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('nama');
            $table->string('kategori')->nullable();          // Masjid Agung, Masjid Raya, dll
            $table->string('tipe')->nullable();              // Masjid / Musholla / dll
            $table->string('no_id_masjid')->nullable();      // 01.2.17.04.03.000001
            $table->year('tahun_berdiri')->nullable();       // 1967

            // Lokasi
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // Kontak & Web
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('url_website')->nullable();
            $table->string('url_peta')->nullable();
            $table->string('url_petunjuk')->nullable();

            // Konten
            $table->longText('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->json('misi')->nullable();

            // Statistik SDM
            $table->unsignedInteger('jumlah_pengurus')->default(0);
            $table->unsignedInteger('jumlah_imam')->default(0);
            $table->unsignedInteger('jumlah_khatib')->default(0);
            $table->unsignedInteger('jumlah_muazin')->default(0);
            $table->unsignedInteger('jumlah_remaja')->default(0);

            // Data fisik
            $table->decimal('luas_tanah_m2', 10, 2)->nullable();       // 780
            $table->string('status_tanah')->nullable();               // Wakaf
            $table->decimal('luas_bangunan_m2', 10, 2)->nullable();    // 0
            $table->unsignedInteger('daya_tampung')->nullable();       // 1000

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosque_profiles');
    }
};

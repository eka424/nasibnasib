<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('masjid_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('masjid_profiles','kategori')) $table->string('kategori')->nullable()->after('nama');
            if (!Schema::hasColumn('masjid_profiles','tipe')) $table->string('tipe')->nullable()->after('kategori');
            if (!Schema::hasColumn('masjid_profiles','no_id_masjid')) $table->string('no_id_masjid')->nullable()->after('tipe');
            if (!Schema::hasColumn('masjid_profiles','tahun_berdiri')) $table->string('tahun_berdiri', 20)->nullable()->after('no_id_masjid');

            if (!Schema::hasColumn('masjid_profiles','kelurahan')) $table->string('kelurahan')->nullable()->after('alamat');
            if (!Schema::hasColumn('masjid_profiles','kecamatan')) $table->string('kecamatan')->nullable()->after('kelurahan');
            if (!Schema::hasColumn('masjid_profiles','kabupaten')) $table->string('kabupaten')->nullable()->after('kecamatan');
            if (!Schema::hasColumn('masjid_profiles','provinsi')) $table->string('provinsi')->nullable()->after('kabupaten');
            if (!Schema::hasColumn('masjid_profiles','kode_pos')) $table->string('kode_pos', 20)->nullable()->after('provinsi');
            if (!Schema::hasColumn('masjid_profiles','lat')) $table->decimal('lat', 10, 7)->nullable()->after('kode_pos');
            if (!Schema::hasColumn('masjid_profiles','lng')) $table->decimal('lng', 10, 7)->nullable()->after('lat');

            if (!Schema::hasColumn('masjid_profiles','email')) $table->string('email')->nullable()->after('lng');
            if (!Schema::hasColumn('masjid_profiles','website')) $table->string('website')->nullable()->after('email');
            if (!Schema::hasColumn('masjid_profiles','url_website')) $table->string('url_website', 500)->nullable()->after('website');
            if (!Schema::hasColumn('masjid_profiles','url_peta')) $table->string('url_peta', 500)->nullable()->after('url_website');
            if (!Schema::hasColumn('masjid_profiles','url_petunjuk')) $table->string('url_petunjuk', 500)->nullable()->after('url_peta');

            if (!Schema::hasColumn('masjid_profiles','misi')) $table->json('misi')->nullable()->after('visi');

            if (!Schema::hasColumn('masjid_profiles','jumlah_pengurus')) $table->unsignedInteger('jumlah_pengurus')->nullable();
            if (!Schema::hasColumn('masjid_profiles','jumlah_imam')) $table->unsignedInteger('jumlah_imam')->nullable();
            if (!Schema::hasColumn('masjid_profiles','jumlah_khatib')) $table->unsignedInteger('jumlah_khatib')->nullable();
            if (!Schema::hasColumn('masjid_profiles','jumlah_muazin')) $table->unsignedInteger('jumlah_muazin')->nullable();
            if (!Schema::hasColumn('masjid_profiles','jumlah_remaja')) $table->unsignedInteger('jumlah_remaja')->nullable();

            if (!Schema::hasColumn('masjid_profiles','luas_tanah_m2')) $table->unsignedInteger('luas_tanah_m2')->nullable();
            if (!Schema::hasColumn('masjid_profiles','status_tanah')) $table->string('status_tanah')->nullable();
            if (!Schema::hasColumn('masjid_profiles','luas_bangunan_m2')) $table->unsignedInteger('luas_bangunan_m2')->nullable();
            if (!Schema::hasColumn('masjid_profiles','daya_tampung')) $table->string('daya_tampung')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('masjid_profiles', function (Blueprint $table) {
            $cols = [
                'kategori','tipe','no_id_masjid','tahun_berdiri',
                'kelurahan','kecamatan','kabupaten','provinsi','kode_pos','lat','lng',
                'email','website','url_website','url_peta','url_petunjuk',
                'misi',
                'jumlah_pengurus','jumlah_imam','jumlah_khatib','jumlah_muazin','jumlah_remaja',
                'luas_tanah_m2','status_tanah','luas_bangunan_m2','daya_tampung',
            ];

            foreach ($cols as $c) {
                if (Schema::hasColumn('masjid_profiles', $c)) $table->dropColumn($c);
            }
        });
    }
};

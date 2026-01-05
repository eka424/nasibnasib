<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('kategori', 20)->default('idarah')->after('tipe'); // idarah|imarah|riayah
            $table->string('seksi', 255)->nullable()->after('kategori');      // nama seksi

            // ✅ kasih nama index biar gampang drop
            $table->index(['kategori', 'seksi'], 'galeris_kategori_seksi_index');
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            // ✅ drop pakai nama index
            $table->dropIndex('galeris_kategori_seksi_index');

            $table->dropColumn(['kategori', 'seksi']);
        });
    }
};

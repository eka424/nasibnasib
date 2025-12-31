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
            $table->index(['kategori', 'seksi']);
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropIndex(['kategori', 'seksi']);
            $table->dropColumn(['kategori', 'seksi']);
        });
    }
};

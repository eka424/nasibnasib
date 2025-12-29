<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('perpustakaans', function (Blueprint $table) {
                $table->string('kategori')->nullable()->after('penulis');
                $table->string('isbn')->nullable()->after('kategori');
                $table->unsignedInteger('stok_total')->default(0)->after('cover');
                $table->unsignedInteger('stok_tersedia')->default(0)->after('stok_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perpustakaans', function (Blueprint $table) {
                $table->dropColumn(['kategori', 'isbn', 'stok_total', 'stok_tersedia']);
        });
    }
};

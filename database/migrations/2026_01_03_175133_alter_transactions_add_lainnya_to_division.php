<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // ubah enum jadi string agar fleksibel (idarah/imarah/riayah/lainnya)
            $table->string('division')->nullable()->change();
        });
    }

    public function down(): void
    {
        // biarin aja, atau kamu kembalikan ke enum kalau mau
    }
};

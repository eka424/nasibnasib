<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('management_positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                ->constrained('management_units')
                ->cascadeOnDelete();

            $table->string('title');          // contoh: "RAIS", "KETUA UMUM", "ANGGOTA"
            $table->string('notes')->nullable(); // contoh: "(BID. IMARAH)" atau keterangan lain
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['unit_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('management_positions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_program_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('work_program_parts')->cascadeOnDelete();
            $table->string('teks'); // contoh: Pelaksanaan Sholat...
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_program_items');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_program_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('work_program_sections')->cascadeOnDelete();
            $table->string('judul'); // contoh: MENYELENGGARAKAN KEGIATAN...
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_program_parts');
    }
};

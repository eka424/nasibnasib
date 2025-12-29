<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mosque_structure_nodes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')->nullable()->constrained('mosque_structure_nodes')->nullOnDelete();

            $table->string('jabatan');              // contoh: Ketua Umum
            $table->string('nama')->nullable();     // contoh: Agus Arianto
            $table->unsignedInteger('urutan')->default(0); // buat sorting

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosque_structure_nodes');
    }
};

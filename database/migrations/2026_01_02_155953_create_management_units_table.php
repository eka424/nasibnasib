<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('management_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('term_id')
                ->constrained('management_terms')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('management_units')
                ->nullOnDelete();

            $table->string('name'); // contoh: "DEWAN SYURO", "BIDANG IMARAH", "SEKSI KESEHATAN"
            $table->enum('type', ['group', 'field', 'section', 'other'])->default('group');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['term_id', 'parent_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('management_units');
    }
};

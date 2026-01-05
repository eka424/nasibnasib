<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('management_terms', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // Judul dokumen/struktur (mis. "MUBES VI 2022")
            $table->string('decision_title')->nullable();   // "KEPUTUSAN MUSYAWARAH BESAR VI ..."
            $table->string('decision_number')->nullable();  // "013 / MUBES-VI / I / 2022"
            $table->string('period_label')->nullable();     // "2022-2026"

            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();

            $table->string('location')->nullable();         // "Gianyar"
            $table->string('decision_date_hijri')->nullable(); // "25 Jumadil Akhir 1443 H"
            $table->date('decision_date_masehi')->nullable();  // 2022-01-28

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_active')->default(false);

            $table->timestamps();

            $table->index(['status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('management_terms');
    }
};

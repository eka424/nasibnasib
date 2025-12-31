<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('sedekah_campaigns', function (Blueprint $table) {
      $table->id();
      $table->string('judul');
      $table->text('deskripsi')->nullable();
      $table->unsignedBigInteger('target_dana')->default(0);
      $table->unsignedBigInteger('dana_terkumpul')->default(0);
      $table->string('poster')->nullable();
      $table->date('tanggal_selesai')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('sedekah_campaigns');
  }
};

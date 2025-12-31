<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('sedekah_transactions', function (Blueprint $table) {
      $table->id();

      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('sedekah_campaign_id')->nullable()->constrained('sedekah_campaigns')->nullOnDelete();

      $table->string('order_id')->unique(); // untuk Midtrans
      $table->unsignedBigInteger('jumlah');

      $table->string('nama')->nullable();
      $table->string('email')->nullable();
      $table->string('whatsapp')->nullable();
      $table->text('pesan')->nullable();

      $table->string('status')->default('pending'); // pending|success|failed|expired|challenge|cancel
      $table->string('payment_type')->nullable();
      $table->string('transaction_id')->nullable();
      $table->string('fraud_status')->nullable();

      $table->string('snap_token')->nullable();
      $table->json('raw_notif')->nullable();

      $table->timestamps();
      $table->index(['status', 'created_at']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('sedekah_transactions');
  }
};

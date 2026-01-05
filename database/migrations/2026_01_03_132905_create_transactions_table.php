<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->date('date');                 // tanggal transaksi
            $table->time('time')->nullable();     // opsional jam

            $table->enum('type', ['income', 'expense']); // pemasukan/pengeluaran

            // Untuk pengeluaran: bidang Idarah/Imarah/Riayah
            $table->enum('division', ['idarah', 'imarah', 'riayah'])->nullable();
            $table->string('subcategory')->nullable();   // mis. listrik, konsumsi, ATK, dll

            $table->string('title'); // WAJIB: "Beli apa" / "Sumber pemasukan"
            $table->text('description')->nullable();

            $table->unsignedBigInteger('amount'); // simpan rupiah tanpa desimal
            $table->string('payment_method')->default('cash'); // cash|transfer|qris|other

            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();

            // bukti transaksi (foto/pdf)
            $table->string('receipt_path')->nullable();
            $table->string('receipt_mime')->nullable();

            // siapa yang input (kalau kamu punya users)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // untuk publik: boleh tampilkan bukti atau tidak
            $table->boolean('is_public')->default(true);

            $table->timestamps();

            $table->index(['date', 'type']);
            $table->index(['division']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_donasis', function (Blueprint $table) {
            $table->string('gateway')->nullable()->after('status_pembayaran');
            $table->string('xendit_invoice_id')->nullable()->after('gateway')->index();
            $table->string('xendit_external_id')->nullable()->after('xendit_invoice_id')->index();
            $table->string('payment_url')->nullable()->after('xendit_external_id');
            $table->timestamp('dibayar_pada')->nullable()->after('payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_donasis', function (Blueprint $table) {
            $table->dropColumn([
                'gateway',
                'xendit_invoice_id',
                'xendit_external_id',
                'payment_url',
                'dibayar_pada',
            ]);
        });
    }
};

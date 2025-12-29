<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\TransaksiDonasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonasiAggregationTest extends TestCase
{
    use RefreshDatabase;

    public function test_donasi_dana_terkumpul_syncs_with_berhasil_transactions(): void
    {
        $admin = User::factory()->role('admin')->create();
        $donasi = Donasi::factory()->create(['dana_terkumpul' => 0]);
        $jamaah = User::factory()->role('jamaah')->create();

        TransaksiDonasi::factory()->create([
            'donasi_id' => $donasi->id,
            'user_id' => $jamaah->id,
            'jumlah' => 200000,
            'status_pembayaran' => 'berhasil',
        ]);

        $pending = TransaksiDonasi::factory()->create([
            'donasi_id' => $donasi->id,
            'user_id' => $jamaah->id,
            'jumlah' => 100000,
            'status_pembayaran' => 'pending',
        ]);

        $donasi->syncDanaTerkumpul();
        $this->assertEquals(200000, $donasi->fresh()->dana_terkumpul);

        $this->actingAs($admin)->put(route('admin.transaksi-donasis.update', $pending), [
            'status_pembayaran' => 'berhasil',
        ])->assertRedirect();

        $this->assertEquals(300000, $donasi->fresh()->dana_terkumpul);

        $donasi->update(['dana_terkumpul' => 0]);

        $this->actingAs($admin)->post(route('admin.donasis.recalc', $donasi))->assertRedirect();

        $this->assertEquals(300000, $donasi->fresh()->dana_terkumpul);
    }
}

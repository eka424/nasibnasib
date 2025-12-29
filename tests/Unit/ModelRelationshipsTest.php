<?php

namespace Tests\Unit;

use App\Models\Artikel;
use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use App\Models\PertanyaanUstadz;
use App\Models\TransaksiDonasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_artikels(): void
    {
        $user = User::factory()->role('pengurus')->create();
        Artikel::factory()->count(2)->for($user)->create();

        $this->assertCount(2, $user->artikels);
    }

    public function test_donasi_has_many_transaksi(): void
    {
        $donasi = Donasi::factory()->create();
        $user = User::factory()->role('jamaah')->create();
        TransaksiDonasi::factory()->count(3)->create([
            'donasi_id' => $donasi->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $donasi->transaksiDonasis);
    }

    public function test_kegiatan_has_many_pendaftar(): void
    {
        $kegiatan = Kegiatan::factory()->create();
        PendaftaranKegiatan::factory()->count(2)->for($kegiatan)->create();

        $this->assertCount(2, $kegiatan->pendaftarans);
    }

    public function test_pertanyaan_relations(): void
    {
        $jamaah = User::factory()->role('jamaah')->create();
        $ustadz = User::factory()->role('ustadz')->create();
        $pertanyaan = PertanyaanUstadz::factory()->create([
            'user_id' => $jamaah->id,
            'ustadz_id' => $ustadz->id,
        ]);

        $this->assertTrue($pertanyaan->penanya->is($jamaah));
        $this->assertTrue($pertanyaan->ustadz->is($ustadz));
    }
}

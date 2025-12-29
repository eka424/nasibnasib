<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranKegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_daftar_kegiatan_twice(): void
    {
        $user = User::factory()->role('jamaah')->create();
        $kegiatan = Kegiatan::factory()->create();

        $this->actingAs($user)->post(route('kegiatan.daftar', $kegiatan))->assertSessionHas('success');
        $this->actingAs($user)->post(route('kegiatan.daftar', $kegiatan))->assertSessionHas('success');

        $this->assertSame(1, PendaftaranKegiatan::where('user_id', $user->id)->where('kegiatan_id', $kegiatan->id)->count());
    }
}

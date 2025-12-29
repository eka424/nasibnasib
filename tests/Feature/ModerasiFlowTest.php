<?php

namespace Tests\Feature;

use App\Models\PertanyaanUstadz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModerasiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderasi_assign_and_answer_flow(): void
    {
        $admin = User::factory()->role('admin')->create();
        $ustadz = User::factory()->role('ustadz')->create();
        $jamaah = User::factory()->role('jamaah')->create();
        $pertanyaan = PertanyaanUstadz::factory()->create([
            'user_id' => $jamaah->id,
            'ustadz_id' => null,
            'status' => 'menunggu',
            'jawaban' => null,
        ]);

        $this->actingAs($admin)->post(route('admin.moderasi.assign', $pertanyaan), [
            'ustadz_id' => $ustadz->id,
        ])->assertRedirect();

        $pertanyaan->refresh();
        $this->assertEquals($ustadz->id, $pertanyaan->ustadz_id);
        $this->assertEquals('menunggu', $pertanyaan->status);

        $this->actingAs($ustadz)->put(route('ustadz.pertanyaan.update', $pertanyaan), [
            'jawaban' => 'Ini jawaban ustadz.',
        ])->assertRedirect();

        $pertanyaan->refresh();
        $this->assertEquals('dijawab', $pertanyaan->status);
        $this->assertEquals('Ini jawaban ustadz.', $pertanyaan->jawaban);
    }
}

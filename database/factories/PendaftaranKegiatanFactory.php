<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PendaftaranKegiatan>
 */
class PendaftaranKegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'kegiatan_id' => Kegiatan::factory(),
            'status' => fake()->randomElement(['menunggu', 'diterima', 'ditolak']),
        ];
    }
}

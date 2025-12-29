<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 day', '+1 month');

        return [
            'nama_kegiatan' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'tanggal_mulai' => $start,
            'tanggal_selesai' => (clone $start)->modify('+2 hours'),
            'lokasi' => fake()->address(),
            'poster' => 'posters/' . fake()->uuid() . '.jpg',
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donasi>
 */
class DonasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
            'target_dana' => fake()->numberBetween(5_000_000, 20_000_000),
            'dana_terkumpul' => 0,
            'tanggal_selesai' => fake()->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}

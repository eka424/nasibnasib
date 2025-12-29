<?php

namespace Database\Factories;

use App\Models\Donasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiDonasi>
 */
class TransaksiDonasiFactory extends Factory
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
            'donasi_id' => Donasi::factory(),
            'jumlah' => fake()->numberBetween(100_000, 1_000_000),
            'status_pembayaran' => fake()->randomElement(['pending', 'berhasil', 'gagal']),
        ];
    }
}

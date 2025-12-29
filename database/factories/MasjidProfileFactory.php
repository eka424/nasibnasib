<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasjidProfile>
 */
class MasjidProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => 'Masjid Al Ala',
            'alamat' => fake()->address(),
            'sejarah' => fake()->paragraphs(3, true),
            'visi' => fake()->sentence(),
            'misi' => fake()->paragraphs(2, true),
        ];
    }
}

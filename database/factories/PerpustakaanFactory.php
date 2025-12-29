<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perpustakaan>
 */
class PerpustakaanFactory extends Factory
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
            'penulis' => fake()->name(),
            'deskripsi' => fake()->paragraph(),
            'file_ebook' => 'ebooks/' . fake()->uuid() . '.pdf',
            'cover' => 'covers/' . fake()->uuid() . '.jpg',
        ];
    }
}

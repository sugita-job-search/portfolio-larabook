<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->realTextBetween(20, 50, 5),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'year' => rand(1970, 2022),
            'month' => rand(1, 12),
            'series_title' => fake()->optional()->realTextBetween(5, 20, 5),
            'genre_id' => rand(1, 30),
            'isbn' => fake()->isbn13(),
            'image' => null,
        ];
    }
}

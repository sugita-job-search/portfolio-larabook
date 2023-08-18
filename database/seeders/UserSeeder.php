<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recommendation;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->create();
        User::factory()
            ->count(5)
            ->has(
                Recommendation::factory()
                    ->count(10)
                    ->hasHearts(10)
                )
            ->create();
        User::factory()
            ->count(5)
            ->has(
                Recommendation::factory()
                    ->count(10)
                    ->hasRecommendationMerits(1)
                )
            ->hasWantToReadBooks(10)
            ->create();
        User::factory()
            ->count(5)
            ->has(
                Recommendation::factory()
                    ->count(10)
                    ->state(['recommendation' => null])
                    ->hasRecommendationMerits(1))
            ->create();
    }
}

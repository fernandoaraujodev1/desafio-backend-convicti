<?php

namespace Database\Factories;

use App\Models\Directorship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unity>
 */
class UnityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'directorship_id' => Directorship::factory(),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
        ];
    }
}

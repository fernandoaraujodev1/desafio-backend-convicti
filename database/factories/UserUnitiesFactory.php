<?php

namespace Database\Factories;

use App\Models\Unity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserUnities>
 */
class UserUnitiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unity_id' => Unity::factory(),
            'user_id' => User::factory(),
        ];
    }
}

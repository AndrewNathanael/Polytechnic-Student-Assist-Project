<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Majoring;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\student>
 */
class StudentFactory extends Factory
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
            'majoring_id' => Majoring::factory(),
            'nim' => $this->faker->unique()->numerify('######'),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}

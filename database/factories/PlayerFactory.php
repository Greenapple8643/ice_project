<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RegisteredPosition;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'playerid' => fake()->unique()->numberBetween(0, 999999999999),
            'first' => fake()->firstNameMale(),
            'last' => fake()->lastName(),
            'emails' => fake()->safeEmail(),
            'dob' => fake()->dateTimeBetween('-18 years', '-7 years')->format('Y/m/d'),
            // 'registered_position' => RegisteredPosition::getRandomName(),
            'accesscode' => UUid::uuid4()->toString(),
            'status' => 1,
        ];
    }
}

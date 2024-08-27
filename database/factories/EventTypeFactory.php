<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventType>
 */
class EventTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Game', 'Exhibition Game', 'Practice', 'Extra Practice', 'Tournament', 'Team Pictures']),
            'requires_ice' => function (array $attributes) {
                $iceRequiredEvents = ['Game', 'Practice', 'Extra Practice', 'Exhibition Game'];
                return in_array($attributes['name'], $iceRequiredEvents) ? true : false;
            },
            'requires_approval' => function (array $attributes) {
                $approvalRequiredEvents = ['Tournament'];
                return in_array($attributes['name'], $approvalRequiredEvents) ? true : false;
            },
            'min_teams' => function (array $attributes) {
                $minTeamsEvents = ['Game'];
                return in_array($attributes['name'], $minTeamsEvents) ? 2 : 1;
            },
            'max_teams' => function (array $attributes) {
                $maxTeamsEvents = ['Game', 'Practice', 'Extra Practice'];
                return in_array($attributes['name'], $maxTeamsEvents) ? 2 : null;
            },
        ];
    }
}

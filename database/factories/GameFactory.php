<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Location;
use App\Models\Org;
use App\Models\Team;
use App\Models\League;


class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeThisMonth();
        $startTime = $date->format('H:i:s');
        $endTime = (clone $date)->modify('+1 hour')->format('H:i:s');
        $dayOfWeek = $date->format('D');

        $team = Team::inRandomOrder()->first();
        $opponent = Team::inRandomOrder()->where('id', '!=', $team->id)->first();

        $location = Location::inRandomOrder()->first();
        $league = League::inRandomOrder()->first();
        $org = Org::inRandomOrder()->first();

        return [
            'org_id' => $org->id,
            'date' => $date->format('Y-m-d'),
            'day' => $dayOfWeek,
            'location_id' => $location->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'league_id' => $league->id,
            'home_team_id' => $team->id,
            'away_team_id' => $opponent->id,
            'home_score' => $this->faker->numberBetween(0, 10),
            'away_score' => $this->faker->numberBetween(0, 10),
            'tie' => false,
            'type' => $this->faker->randomElement(['Regular', 'Playoffs', 'Final']),
            'status' => $this->faker->randomElement(['Scheduled', 'Finished', 'Cancelled'])
        ];
    }
}

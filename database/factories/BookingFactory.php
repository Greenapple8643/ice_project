<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Location;
use App\Models\Org;
use App\Models\Team;
use App\Models\League;
use App\Models\Program;


class BookingFactory extends Factory
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
        $org = Org::inRandomOrder()->first();
        $program = Program::inRandomOrder()->first();
        $league = League::inRandomOrder()->first();
        $location = Location::inRandomOrder()->first();

        return [
            'org_id' => $org->id,
            'used' => false,
            'program_id' => $program->id,
            'league_id' => $league->id,
            'status' => "Acquired",
            'cost' => $this->faker->numberBetween(50, 100),
            'location_id' => $location->id,
            'booking_date' => $date->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}

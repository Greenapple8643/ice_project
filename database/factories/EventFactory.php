<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventType;
use App\Models\Location;
use App\Models\Org;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $org_id = Org::inRandomOrder()->first()->id;
        $location = Location::inRandomOrder()->first();
        $eventType = EventType::inRandomOrder()->first();
        return [
            'org_id' => $org_id,
            'date' => $this->faker->dateTimeThisMonth(),
            'location_id' => $eventType->requires_ice ? $location->id : null,
            'location_external' => $eventType->requires_ice ? null : $this->faker->address(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'type_id' => $eventType->id,
            'booking_id' => null,
            'status' => $this->faker->randomElement(['Added', 'Rescheduled']),
        ];
    }
}

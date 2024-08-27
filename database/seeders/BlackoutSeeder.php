<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlackoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blackouts')->insert([
            [
                'org_id' => 1,
                'event_type' => 'Maintenance',
                'start_datetime' => Carbon::parse('2024-07-15 08:00:00'),
                'end_datetime' => Carbon::parse('2024-07-15 12:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_id' => 1,
                'event_type' => 'Event',
                'start_datetime' => Carbon::parse('2024-07-16 09:00:00'),
                'end_datetime' => Carbon::parse('2024-07-16 11:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_id' => 2,
                'event_type' => 'Maintenance',
                'start_datetime' => Carbon::parse('2024-07-17 10:00:00'),
                'end_datetime' => Carbon::parse('2024-07-17 14:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_id' => 2,
                'event_type' => 'Event',
                'start_datetime' => Carbon::parse('2024-07-18 12:00:00'),
                'end_datetime' => Carbon::parse('2024-07-18 15:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_id' => 3,
                'event_type' => 'Maintenance',
                'start_datetime' => Carbon::parse('2024-07-19 08:00:00'),
                'end_datetime' => Carbon::parse('2024-07-19 10:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_id' => 3,
                'event_type' => 'Event',
                'start_datetime' => Carbon::parse('2024-07-20 09:00:00'),
                'end_datetime' => Carbon::parse('2024-07-20 12:00:00'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


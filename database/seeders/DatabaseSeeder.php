<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            OrgSeeder::class,
            LocationNMHASeeder::class,
            LocationSeeder::class,


            UserSeeder::class,

            SeasonSeeder::class,
            ProgramSeeder::class,
            DivisionSeeder::class,
            TierSeeder::class,

            LeagueSeeder::class,
            TeamSeeder::class,
            // UserSeeder::class,
            // SeasonSeeder::class,
            // SportSeeder::class,

            EventTypeSeeder::class,
            EventSeeder::class,
            
            BlackoutSeeder::class,
        ]);

    }
}

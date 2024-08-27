<?php

namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Org;

class LocationSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     */
    // public function __construct()
    // {
    //     $this->file = '/database/data/locations.csv';
    //     $this->mapping = ['org_id','abbr', 'name', 'address', 'map_url'];
    //     $this->header = TRUE;
    //     $this->delimiter = '|';
    // }
    public function run(): void
    {
        //
        $orgs = Org::all();
        foreach ($orgs as $org) {
            if ($org->abbr <> "NMHA") {
            print("\t" . $org->name . "\n");
            // foreach($org->teams()->get() as $team) {
                $count = fake()->numberBetween(5, 10);
                // print("\t\t" . $team->abbr . "[" . $count . "]\n");
                Location::factory()->count($count)->create([
                    'org_id' => $org->id,
                    // 'team_id' => $team->id,
                ]);
            }
        }
    }
}

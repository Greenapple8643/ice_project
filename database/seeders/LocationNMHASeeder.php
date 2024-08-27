<?php

namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Org;

class LocationNMHASeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     */
    public function __construct()
    {
        $this->file = '/database/data/locations.csv';
        $this->mapping = ['org_id','abbr', 'name', 'address', 'map_link'];
        $this->header = TRUE;
        $this->delimiter = '|';
    }
}

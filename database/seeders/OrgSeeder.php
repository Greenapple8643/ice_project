<?php

namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrgSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/data/orgs.csv';
        $this->mapping = ['abbr', 'name', 'domain', 'website', 'logo'];
        $this->header = TRUE;
        $this->delimiter = ',';
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        parent::run();
    }
}

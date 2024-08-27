<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $orgs = Org::all();
        foreach ($orgs as $org) {
            print ("\t" . $org->abbr .  "\n");
            Program::factory()->houseleague()->create([
                'org_id' => $org->id,
            ]);
            Program::factory()->competitive()->create([
                'org_id' => $org->id,
            ]);
        }
    }
}

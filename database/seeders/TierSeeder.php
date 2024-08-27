<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
use App\Models\Tier;

class TierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tiers = ["AAA", "AA", "A", "B", "C"];
        $orgs = Org::all();

        foreach ($orgs as $org) {
            print ("\t". $org->abbr . "\n");
            foreach ($tiers as $tier) {
                $tierobj = New Tier;
                $tierobj->org_id = $org->id;
                $tierobj->name = $tier;
                $tierobj->save();
                print ("\t\t". $tierobj->name . "\n");
            }
        }    
    }
}

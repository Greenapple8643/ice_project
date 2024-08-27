<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
use App\Models\Division;

class DivisionSeeder extends Seeder
{

    public function run(): void
    {
        $divisions = ["U7", "U9", "U11", "U13", "U15", "U18", "U21"];
        $orgs = Org::all();

        foreach ($orgs as $org) {
            print ("\t". $org->abbr . "\n");
            foreach ($divisions as $div) {
                $age = strval(substr($div,1));
                $division = New Division;
                $division->org_id = $org->id;
                $division->age_as_of = '0000/12/31';
                
                if ($age > 15) {
                    $division->min_age = $age - 3;
                }
                else {
                    $division->min_age = $age - 2;
                }
                
                $division->max_age = $age - 1; 
                $division->name = $div;
                $division->save();

                print ("\t\t". $division->name . "\n");
            }
        }        
    }
}

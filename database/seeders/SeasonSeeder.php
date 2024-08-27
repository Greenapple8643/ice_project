<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $orgs = Org::all();
        foreach ($orgs as $org) {
            $year = intval(date('Y'));
            print ("\t" . $org->abbr .  "\n");
            for ($x = ($year - 5); $x <= $year; $x++) {
                print ("\t\t" . $x .  "\n");
                $y = $x + 1;
                $season = new Season;
                $season->org_id = $org->id;
                $season->name = $org->abbr . "-" . $x . "-". $y;
                $season->start_date = "{$x}/09/01";
                $season->end_date = "{$y}/05/01";
                $season->age_date = "{$x}/12/31";
                
                if ($year == $x) {
                    $season->active = TRUE ;
                }
                else {
                    $season->active = FALSE ;
                }
                $season->save();
                // $season->end_date = "{$y} . /05/01";
            }
            // print ("\t\t" . $year .  "\n");
            // $x = $year;
            // $y = $year + 1;
            // $season = new Season;
            // $season->org_id = $org->id;
            // $season->name = $org->abbr . "-" . $x . "-". $y;;
            // $season->start_date = "{$x}/09/01";
            // $season->end_date = "{$y}/05/01";
            // $season->age_date = "{$x}/12/31";
            // $season->active = TRUE;
            // $season->save();
        }
    }
}

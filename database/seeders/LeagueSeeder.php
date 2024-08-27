<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
use App\Models\Season;
use App\Models\Program;
use App\Models\Division;
use App\Models\League;
use App\Models\Tier;


class LeagueSeeder extends Seeder
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
            foreach ($org->getActiveSeasons() as $season) {
                print ("\t\t" . $season->name .  "\n");
                foreach ($org->programs()->get() as $program) {
                    print ("\t\t\t" . $program->abbr .  "\n");
                    foreach ($org->divisions()->get() as $division) {      
                        print ("\t\t\t\t" . $division->name .  "\n");
                        foreach ($org->tiers()->get() as $tier) {
                            if (strtoupper($program->abbr) == "COMP") {
                                if (in_array($tier->name,["AAA","AA","B"])){
                                    print ("\t\t\t\t\t" . $tier->name .  "\n");
                                    $this->buildLeague($org, $season, $program, $division, $tier);
                                }
                            }
                            if ($program->abbr == "HL") {
                                if (in_array($tier->name,["A","B","C"])){
                                    print ("\t\t\t\t\t" . $tier->name .  "\n");
                                    $this->buildLeague($org, $season, $program, $division, $tier);
                                }
                            }
                        }    
                    }    
                }
            }
        }
    }

    public function buildLeague(Org $org, Season $season, Program $program, Division $division, Tier $tier) {
        $divname = "";
        $divname = $season->name . "-" . $program->abbr . "-" . $division->name . "-" . $tier->name; 
        print ("\t\t\t\t\t\t" . strtoupper($divname) .  "\n");
        $league = new League;
        $league->name = $divname;
        $league->org_id = $org->id;
        $league->season_id = $season->id;
        $league->program_id = $program->id;
        $league->division_id = $division->id;
        $league->tier_id = $tier->id;
        $league->save();
    }
}

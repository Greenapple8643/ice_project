<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Org;
// use App\Models\League;
// use App\Models\Program;
use App\Models\Team;
use App\Models\Player;


class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $orgs = Org::all();
        // $orgs = Org::first();
        foreach ($orgs as $org) {
            // $leagues = $org->leagues()->get();
            // $leagues = $org->leagues()->where('division_id','=',1)->get();
            // print($leagues);
            // foreach ($leagues as $league) {
            foreach ($org->leagues()->get() as $league) {
                // if ($league->division_id == 1) {
                    print ("\t" . $league->name . "\n");
                    $tier = $league->tier()->first()->name;
                    $program = $league->program()->first()->abbr;
                    // print ("\t\t\t" . $tier . $program . "\n");
                    switch ($tier) {
                        case "A" : {
                            if ($program == "HL") {
                                $count = rand(2,3) * 2;
                            }
                            else {
                                $count = 1;
                            }
                            break;
                        }
                        case "B" : {
                            if ($program == "HL") {
                                $count = rand(5,6) * 2;
                            }
                            else {
                                $count = rand(1,2);
                            }
                            break;
                        }
                        case "C" : {
                            $count = rand(2,3) * 2;
                            break;
                        }

                        case "AA" : {
                            $count = 1;
                            break;
                        }

                        default : {
                            $count = 1;
                        }
                    }
                    print ("\t\t" . $tier . " - " . $count . "\n");
                    for ($x = 1; $x <= $count; $x++) {
                        $team = new Team;
                        $team->abbr = chr(64 + $x);
                        $team->org_id = $org->id;
                        $team->league_id = $league->id;
                        $team->name = "Team " . chr(64 + $x);
                        $team->save();
                        if ($league->org_id == 1 && $league->division_id == 1) {
                            $teamplayers = Player::factory(17)->create(['org_id' => $org->id, 'season_id' => $league->season->first()->id]);
                            // foreach ($teamplayers as $player) {
                            //     print ($player->first);
                            // }
                            $team->players()->saveMany($teamplayers);
                        }
                    }
                }
            // }
        }
    }
}

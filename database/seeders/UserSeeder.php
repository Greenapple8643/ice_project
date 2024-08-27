<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
// use Database\Helpers\UserHelper;
// use Database\Helpers\TeamHelper;
use App\Models\Org;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        // $superadmin = UserHelper::CreateUser("evalmgr.com", "Admin");
        $admin= User::factory()->create(['name' => 'Eval Manager Admin', 'email' => 'admin@evalmgr.com']);

        $orgs = Org::all();
        foreach ($orgs as $org) {
            $name = $org->abbr . " Admin";
            $email = 'admin@' . $org->domain;
            $user = User::factory()->create(['name' => $name, 'email' => $email]);
            $user->orgs()->save($org);
            $admin->orgs()->save($org);
            if ($org->abbr == "NMHA") {
                $user->last_login_at = now();
                $user->save();
            }
            $user->current_orgid = $org->id;
            print ("\t\t". $user->email . "\n");

        }        
    }
}

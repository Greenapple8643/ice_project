<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

class EnsureUserDefaultsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();
        // Log::info("HAS TENANCY");     
        if (! $panel->hasTenancy()) {
            return $next($request);
        }

        // Log::info("TENTANT PARAMETER");     
        if (! $request->route()->hasParameter('tenant')) {
            return $next($request);
        }

        User:$user = $panel->auth()->user();
        Log::info("USER " . $user);

        if ($user == NULL) {    
            return $next($request);
        }   
        $tenant = $panel->getTenant($request->route()->parameter('tenant'));
        Log::info("TENANT" . $tenant);     

        if ($user->current_org == NULL) {
            // Log::info("CURRENT ORG NULL");     
            if ($user->orgs()->find($tenant->id) != NULL) {
                Log::info("CONTAINS TENANT");
                $user->current_org = $tenant->id;
                $user->save();

            }
        }
        if ($tenant->id != $user->current_org) {
            Log::info("UPDATE CURRENT ORG");
            $user->current_org = $tenant->id;
            $user->save();
        }
        else {
            Log::info("CURRENT ORG SET PROPERLY");
        }

        // Log::info("User: " . $user->name . " accessing tenant " . $tenant);
        // if ($panel->getTenantMenuItems() == []) {
        //     Log::info("Loading TenantMenuItem for tenant " . $tenant);
        //     $menu = array();
        //     $menu = $panel->getTenantMenuItems();
            
            // $teams = Team::where('org_id', $tenant->id)->get();
            // $teams = $user->teams()->get();
            // if ($teams->count() == 0) {
            //     $user->current_team == NULL;
            //     $user->save();   
            // }
            // else {
            //     if ($user->current_team == NULL) {
            //         $user->current_team = $teams->first()->id;
            //         $user->save();   
            //     }
            //     else {
            //         if ($teams->where('id', $user->current_team)->count() == 0) {
            //             # NO MATCH FOUND - RESET LAST TEAM
            //             $user->current_team = $teams->first()->id;
            //             $user->save();   
            //         }
            //     }

                // foreach ($teams as $team) {
                    
                //     if ($team->id == $user->current_team){
                //         $icon = "heroicon-o-check-circle";
                //         $colour = "success";
                //     }
                //     else {
                //         $icon = "heroicon-o-minus-circle";
                //         $colour = "";
                //     }

                //     // Log::info($team->name);
                //     $item = MenuItem::make()
                //         ->label($team->abbr)
                //         // ->url('/org/' . $tenant->id)
                //         ->url(route('switch.show', ['tenant' => $tenant->id, 'switch' => $team->id], false))
                //         // ->url('http://127.0.0.1:8000')
                //         ->icon($icon)
                //         ->color($colour);
                //     $menu[] = $item;
                // }
            // }
            // $panel->tenantMenuItems($menu);
        // }

        // dd($panel);

        return $next($request);

    }
}

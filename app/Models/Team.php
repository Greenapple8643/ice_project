<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Team extends Model
{
    use HasFactory;


    public function org(): BelongsTo
    {
        return $this->belongsTo(Org::class);
    }

    public function isOrg(): bool
    {
        return $this->type == 0;
    }

    public function divisions()
    {
        if ($this->division_id == NULL) {
            // print("NULL\n");
            return $this->org()->divisions()->get();
        }
        else {
            // print("DIVISION\n");
            // print($this->division_id);
            return Division::find($this->division_id);
        }
    }
    public function members(): BelongsToMany
    {
        // return $this->hasMany(TeamMember::class);
        return $this->BelongsToMany(Player::class, 'team_member');
    }

    public function players(): BelongsToMany
    {
        // return $this->hasMany(TeamMember::class);
        return $this->BelongsToMany(Player::class);
    }

    public function xxxsearchAvailablePlayers($search, $limit = 50)
    {
        // Log::info($search);
        // Log::info($this->id);
        // Log::info($this->players()->count());
        $unavailable = $this->players()->pluck('playerid');
        // Log::info($unavailable);
        // return $unavailable;
        return Player::select(DB::raw("CONCAT(players.playerid, ' (' ,players.first,' ',players.last, ')') as name"),'id')
                ->where(function ($query) use ($search) {
                    // $search ="bern";
                    $query->Where('first', 'like', "%{$search}%")
                    ->orWhere('last','like',"%{$search}%")
                    ->orWhere('playerid','like',"%{$search}%");
                })
                ->where(function ($query) use ($unavailable) {
                    $query->WhereNotIn('playerid', $unavailable);
                })
                ->limit($limit)
                ->pluck('name','id')
                ->toArray();
    }

    public function searchAvailablePlayers($search, $limit = 50)
    {
        $unavailable = $this->players()->pluck('playerid');
        return Player::where(function ($query) use ($search) {
                    $query->Where('first', 'like', "%{$search}%")
                    ->orWhere('last','like',"%{$search}%")
                    ->orWhere('playerid','like',"%{$search}%");
                })
                ->where(function ($query) use ($unavailable) {
                    $query->WhereNotIn('playerid', $unavailable);
                })
                ->limit($limit)
                ->pluck('label','id')
                ->toArray();
    }

    public function getAvailablePlayers()
    {
        $unavailable = $this->players()->pluck('playerid');
        return Player::WhereNotIn('playerid', $unavailable)
                ->pluck('label','id')
                ->toArray();
    }

    public function getOptionLabelUsing($value)
    {
        Log::info("Value " . $value);        
        return Player::select(DB::raw("CONCAT(players.playerid, ' (' ,players.first,' ',players.last, ')') as name"),'id')
                ->where('id',$value)->first()->name;
    }

    public function leagues()
    {
        $query = League::query();
        return League::getQuery($this, $query)->get();
    }

    public function locations()
    {
        $query = Location::query();
        return Location::getQuery($this, $query)->get();
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }
}

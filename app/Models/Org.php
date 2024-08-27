<?php

namespace App\Models;


use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Org extends Model implements HasAvatar
{
    use HasFactory;

    public function getFilamentAvatarUrl(): ?string
    {
        return ($this->logo);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }


    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(Tier::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function getActiveSeasons()
    {
        return Season::all()->where('org_id','=', $this->id)
        ->where('active','=',TRUE);
    }

    public function getActiveLeagues()
    {
        return League::all()->where('org_id','=', $this->id);
        // ->where('active','=',TRUE);
    }

    public function blackouts(): HasMany
    {
        return $this->hasMany(Blackout::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'day',
        'date',
        'location_id',
        'start_time',
        'end_time',
        'league_id',
        'home_team_id',
        'away_team_id',
        //'home_event_id',
        //'away_event_id',
        'home_score',
        'away_score',
        'tie',
        'type',
        'status'
    ];

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id'); // New relationship
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /*public function homeEvent()
    {
        return $this->belongsTo(Event::class, 'home_event_id');
    }

    public function awayEvent()
    {
        return $this->belongsTo(Event::class, 'away_event_id');
    }*/
};
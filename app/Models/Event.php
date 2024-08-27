<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['org_id', 'date','location_id','location_external', 'cost', 'start_time', 'end_time', 'type_id', 'booking_id', 'status', 'home_team_id', 'away_team_id'];

    public function org()
    {
        return $this->belongsTo(Org::class);
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function availableBookings()
    {
        return Booking::where('location_id', $this->location_id)
            ->whereDate('date', $this->date)
            ->whereNull('event_id')
            ->get();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_id');
    }
}

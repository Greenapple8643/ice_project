<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;    
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Division;
use Illuminate\Support\Carbon;

class Player extends Model
{
    use HasFactory;
    
    public function org(): BelongsTo
    {
        return $this->belongsTo(Org::class);
    }   
    
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function tier(): BelongsTo
    {
        return $this->belongsTo(Tier::class);
    }

    public function age(): Int
    {
        $dob = Carbon::parse($this->dob);
        $asof = Carbon::createFromFormat('Y-m-d', $this->season()->first()->age_date);
        $age = $dob->diffInYears($asof);
        return $age;
    }

    public function calcDivision(): Division
    {
        $age = $this->age();
        return $this->org()->first()->divisions()->where('max_age', ">=", $age)->where("min_age","<=", $age)->get()->first();
    }
}

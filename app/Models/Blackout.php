<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Blackout extends Model
{
    use HasFactory;

    public function org(): BelongsTo
    {
        return $this->belongsTo(Org::class);
    }

    protected $fillable = [
        'event_type',
        'start_datetime',
        'end_datetime',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];
}

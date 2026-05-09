<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactRequest extends Model
{
    protected $fillable = [
        'hostel_id',          // ← AJOUTE
        'first_name',
        'last_name',
        'email',
        'phone',
        'destination',
        'arrival_date',
        'departure_date',
        'travelers',
        'room_type',
        'message',
        'status',
    ];

    protected $casts = [
        'arrival_date'   => 'date',
        'departure_date' => 'date',
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }
}
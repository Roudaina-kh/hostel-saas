<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    protected $fillable = [
        'hostel_id', 'room_id', 'price_amount',
        'currency', 'valid_from', 'valid_to', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'valid_from' => 'date',
        'valid_to'   => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}
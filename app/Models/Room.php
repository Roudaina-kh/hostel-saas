<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'hostel_id', 'name', 'type',
        'min_capacity', 'max_capacity',
        'status', 'description',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function prices()
    {
        return $this->hasMany(RoomPrice::class);
    }

    public function activePrice()
    {
        return $this->hasOne(RoomPrice::class)->where('is_active', true)->latestOfMany();
    }
}
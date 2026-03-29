<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['hostel_id', 'room_id', 'guest_name', 'start_date', 'end_date', 'status'];
    
    public function hostel() { return $this->belongsTo(Hostel::class); }
}

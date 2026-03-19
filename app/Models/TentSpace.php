<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentSpace extends Model
{
    protected $fillable = [
        'hostel_id', 'name', 'max_tents', 'status', 'description',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}
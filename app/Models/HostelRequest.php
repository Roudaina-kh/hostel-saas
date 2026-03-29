<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_name',
        'first_name',
        'last_name',
        'email',
        'country',
        'city',
        'phone',
        'channel_manager',
        'status',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(HostelUser::class)
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function tentSpaces()
    {
        return $this->hasMany(TentSpace::class);
    }

    public function taxSetting()
    {
        return $this->hasOne(TaxSetting::class);
    }
}
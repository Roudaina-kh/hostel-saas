<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Owner extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'plan'];

    protected $hidden = ['password', 'remember_token'];

    public function hostels()
    {
        return $this->hasMany(Hostel::class);
    }
}
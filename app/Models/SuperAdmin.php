<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'last_login_at'  => 'datetime',
    ];

    // Super Admin peut voir tous les owners
    public function owners()
    {
        return Owner::all();
    }

    // Super Admin peut voir tous les hostels
    public function hostels()
    {
        return Hostel::all();
    }

    // Vérifier si le super admin est actif
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
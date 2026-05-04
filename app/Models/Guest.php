<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'identity_card',
        'email',
        'phone',
        'country_id',
        'gender',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function mainReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'main_guest_id');
    }

    public function reservationPeople(): HasMany
    {
        return $this->hasMany(ReservationPerson::class);
    }

    // Accessor : nom complet
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
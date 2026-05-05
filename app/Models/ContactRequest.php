<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone',
        'destination', 'arrival_date', 'departure_date',
        'travelers', 'room_type', 'message', 'status',
    ];

    protected function casts(): array
    {
        return [
            'arrival_date'   => 'date',
            'departure_date' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new'     => 'Nouveau',
            'read'    => 'Lu',
            'replied' => 'Répondu',
            default   => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new'     => 'danger',
            'read'    => 'warning',
            'replied' => 'success',
            default   => 'secondary',
        };
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
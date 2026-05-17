<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationPerson extends Model
{
    protected $fillable = [
        'reservation_id',
        'guest_id',
        'display_name',
        'nationality',
        'item_type',
        'item_id',
        'price_tnd',
        'price_input',
        'currency',
        'exchange_rate',
        'is_checked_in',
    ];

    protected $casts = [
        'price_tnd'     => 'decimal:3',
        'price_input'   => 'decimal:3',
        'exchange_rate' => 'decimal:4',
        'is_checked_in' => 'boolean',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    // Relation dynamique vers l'unité
    public function item(): BelongsTo
    {
        return match ($this->item_type) {
            'bed'        => $this->belongsTo(Bed::class, 'item_id'),
            'room'       => $this->belongsTo(Room::class, 'item_id'),
            'tent_space' => $this->belongsTo(TentSpace::class, 'item_id'),
            default      => $this->belongsTo(Bed::class, 'item_id'),
        };
    }

    // Helper : nom de l'unité
    public function getItemNameAttribute(): string
    {
        return $this->item?->name ?? 'N/A';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationExtra extends Model
{
    protected $fillable = [
        'reservation_id',
        'extra_id',
        'quantity',
        'price_tnd',
    ];

    protected function casts(): array
    {
        return [
            'quantity'  => 'integer',
            'price_tnd' => 'decimal:3',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function extra(): BelongsTo
    {
        return $this->belongsTo(Extra::class);
    }
}
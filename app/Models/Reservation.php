<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'hostel_id',
        'main_guest_id',
        'start_date',
        'end_date',
        'nights',
        'total_guests',
        'status',
        'source',
        'total_price_tnd',
        'total_price_eur',
        'total_price_usd',
        'extras_total_tnd', // ✅ ajouté
        'notes',
        'created_by',
        'user_id',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'total_price_tnd'   => 'decimal:3',
        'total_price_eur'   => 'decimal:3',
        'total_price_usd'   => 'decimal:3',
        'extras_total_tnd'  => 'decimal:3',
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function mainGuest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'main_guest_id');
    }

    public function people(): HasMany
    {
        return $this->hasMany(ReservationPerson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relation extras
    public function extras(): HasMany
    {
        return $this->hasMany(ReservationExtra::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    public function scopeForHostel($query, int $hostelId)
    {
        return $query->where('hostel_id', $hostelId);
    }

    public function scopeOverlapping($query, string $start, string $end)
    {
        return $query->where('start_date', '<', $end)
                     ->where('end_date', '>', $start);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tax extends Model
{
    protected $fillable = [
        'hostel_id',
        'name',
        'type',
        'amount',
        'is_enabled',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount'     => 'decimal:3',
            'is_enabled' => 'boolean',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function prices(): BelongsToMany
    {
        return $this->belongsToMany(Price::class, 'price_tax')
            ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    protected $fillable = [
        'hostel_id',
        'name',
        'type',
        'max_capacity',
        'is_enabled',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled'    => 'boolean',
            'max_capacity'  => 'integer',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function inventoryBlocks(): MorphMany
    {
        return $this->morphMany(InventoryBlock::class, 'blockable');
    }

    // ─── Helpers métier ──────────────────────────────────────────────────────

    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isDormitory(): bool
    {
        return $this->type === 'dormitory';
    }
    public function activePrice()
{
    return $this->morphOne(\App\Models\Price::class, 'priceable')
        ->where('valid_from', '<=', now()->toDateString())
        ->where(function ($q) {
            $q->whereNull('valid_to')
              ->orWhere('valid_to', '>=', now()->toDateString());
        })
        ->orderByDesc('valid_from');
}
}
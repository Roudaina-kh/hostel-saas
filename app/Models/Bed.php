<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bed extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function inventoryBlocks(): MorphMany
    {
        return $this->morphMany(InventoryBlock::class, 'blockable');
    }

    // ─── Helpers métier ──────────────────────────────────────────────────────

    /**
     * Accès au hostel via la room (pas de hostel_id direct dans beds).
     */
    public function getHostelIdAttribute(): int
    {
        return $this->room->hostel_id;
    }
}
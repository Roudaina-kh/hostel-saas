<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TentSpace extends Model
{
    protected $fillable = [
        'hostel_id',
        'name',
        'max_tents',
        'max_persons',
        'is_enabled',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled'  => 'boolean',
            'max_tents'   => 'integer',
            'max_persons' => 'integer',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function inventoryBlocks(): MorphMany
    {
        return $this->morphMany(InventoryBlock::class, 'blockable');
    }
}
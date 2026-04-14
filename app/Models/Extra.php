<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Extra extends Model
{
    protected $fillable = [
        'hostel_id',
        'name',
        'description',
        'stock_mode',
        'stock_quantity',
        'stock_alert_threshold',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled'            => 'boolean',
            'stock_quantity'        => 'integer',
            'stock_alert_threshold' => 'integer',
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

    public function stockMovements(): HasMany
    {
        return $this->hasMany(ExtraStockMovement::class);
    }

    // ─── Helpers métier ──────────────────────────────────────────────────────

    public function hasTrackedStock(): bool
    {
        return in_array($this->stock_mode, ['consumable', 'rentable']);
    }

    public function isLowStock(): bool
    {
        if (! $this->hasTrackedStock() || is_null($this->stock_alert_threshold)) {
            return false;
        }

        return $this->stock_quantity <= $this->stock_alert_threshold;
    }
}
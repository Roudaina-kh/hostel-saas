<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraStockMovement extends Model
{
    /**
     * Types qui augmentent le stock.
     */
    const STOCK_IN_TYPES = ['initial', 'purchase', 'adjustment_in', 'return'];

    /**
     * Types qui diminuent le stock.
     */
    const STOCK_OUT_TYPES = ['adjustment_out', 'damage', 'loss'];

    protected $fillable = [
        'hostel_id',
        'extra_id',
        'movement_type',
        'quantity',
        'note',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    // ─── Boot : mise à jour automatique du stock ──────────────────────────────

    protected static function booted(): void
    {
        static::created(function (ExtraStockMovement $movement) {
            $extra = $movement->extra;

            if (! $extra->hasTrackedStock()) {
                return;
            }

            if (in_array($movement->movement_type, self::STOCK_IN_TYPES)) {
                $extra->increment('stock_quantity', $movement->quantity);
            } elseif (in_array($movement->movement_type, self::STOCK_OUT_TYPES)) {
                $extra->decrement('stock_quantity', $movement->quantity);
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function extra(): BelongsTo
    {
        return $this->belongsTo(Extra::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Helpers métier ──────────────────────────────────────────────────────

    public function isStockIn(): bool
    {
        return in_array($this->movement_type, self::STOCK_IN_TYPES);
    }

    public function isStockOut(): bool
    {
        return in_array($this->movement_type, self::STOCK_OUT_TYPES);
    }
}
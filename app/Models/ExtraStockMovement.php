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
    // NOTE : Le controller NE doit PAS aussi appeler increment/decrement.
    // La mise à jour du stock est gérée ICI uniquement pour éviter le double-update.

    protected static function booted(): void
    {
        static::created(function (ExtraStockMovement $movement) {
            $extra = $movement->extra;

            if (! $extra->hasTrackedStock()) {
                return;
            }

            if ($movement->isStockIn()) {
                $extra->increment('stock_quantity', $movement->quantity);
            } elseif ($movement->isStockOut()) {
                $extra->decrement('stock_quantity', $movement->quantity);
            }
        });

        static::deleted(function (ExtraStockMovement $movement) {
            $extra = $movement->extra;

            if (! $extra->hasTrackedStock()) {
                return;
            }

            // Annulation : on inverse l'impact sur le stock
            if ($movement->isStockIn()) {
                $extra->decrement('stock_quantity', $movement->quantity);
            } elseif ($movement->isStockOut()) {
                $extra->increment('stock_quantity', $movement->quantity);
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

    /**
     * Alias utilisé dans la blade pour afficher ↑ / ↓.
     */
    public function isIncrease(): bool
    {
        return $this->isStockIn();
    }

    /**
     * Retourne la quantité signée (+/-) selon le type de mouvement.
     * Utilisé uniquement si on a besoin du delta en dehors du boot.
     */
    public function getSignedQuantity(): int
    {
        return $this->isStockIn() ? $this->quantity : -$this->quantity;
    }
}
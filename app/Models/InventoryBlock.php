<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryBlock extends Model
{
    protected $fillable = [
        'hostel_id',
        'blockable_type',
        'blockable_id',
        'block_type',
        'start_date',
        'end_date',
        'reason',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }

    // ─── Helpers métier ──────────────────────────────────────────────────────

    /**
     * Indique si le bloc est encore actif à une date donnée.
     */
    public function isActiveOn(\Carbon\Carbon $date): bool
    {
        if ($date->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $date->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Indique si le bloc est indéfini (sans date de fin).
     */
    public function isOpenEnded(): bool
    {
        return is_null($this->end_date);
    }
}
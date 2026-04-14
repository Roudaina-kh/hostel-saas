<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    protected $fillable = [
        'hostel_id',
        'priceable_type',
        'priceable_id',
        'pricing_mode',
        'price_ht',
        'price_ttc',
        'valid_from',
        'valid_to',
    ];

    protected function casts(): array
    {
        return [
            'price_ht'   => 'decimal:2',
            'price_ttc'  => 'decimal:2',
            'valid_from' => 'date',
            'valid_to'   => 'date',
        ];
    }

    // ─── Règles de cohérence pricing_mode / type ──────────────────────────────
    // private room  → per_room
    // dormitory     → per_bed
    // tent_space    → per_person
    // extra         → per_unit | per_night | per_person | per_person_per_night

    // ─── Relations ───────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function taxes(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'price_tax')
            ->withTimestamps();
    }
}
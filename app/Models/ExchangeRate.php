<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'hostel_id',
        'currency',
        'buy_rate_to_tnd',
        'sell_rate_to_tnd',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'buy_rate_to_tnd'  => 'decimal:4',
            'sell_rate_to_tnd' => 'decimal:4',
        ];
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Taux actif pour une devise : toujours le plus récent
     * Utilisation : ExchangeRate::active('EUR', $hostelId)
     */
    public static function active(string $currency, int $hostelId): ?self
    {
        return static::where('hostel_id', $hostelId)
            ->where('currency', $currency)
            ->latest()
            ->first();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'reservation_person_id',  // NULL = paiement global
        'user_id',
        'amount_tnd',
        'amount_input',
        'currency',
        'exchange_rate',
        'payment_method',
        'status',
        'received_by',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount_tnd'            => 'decimal:3',
            'amount_input'          => 'decimal:3',
            'exchange_rate'         => 'decimal:4',
            'reservation_person_id' => 'integer',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function reservationPerson(): BelongsTo
    {
        return $this->belongsTo(ReservationPerson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Staff qui a encaissé
    }

    // ── Accesseurs ────────────────────────────────────────────────

    /**
     * Paiement global (pas lié à une personne spécifique)
     */
    public function isGlobal(): bool
    {
        return is_null($this->reservation_person_id);
    }

    /**
     * Libellé lisible du mode de paiement
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'     => 'Espèces',
            'card'     => 'Carte',
            'transfer' => 'Virement',
            'other'    => 'Autre',
            default    => $this->payment_method,
        };
    }

    /**
     * Libellé lisible du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid'  => 'Non payé',
            'partial' => 'Partiel',
            'paid'    => 'Payé',
            default   => $this->status,
        };
    }

    /**
     * Couleur badge pour le statut (CSS class)
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid'    => 'success',
            'partial' => 'warning',
            'unpaid'  => 'danger',
            default   => 'secondary',
        };
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partial']);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('reservation_person_id');
    }

    public function scopePerPerson($query)
    {
        return $query->whereNotNull('reservation_person_id');
    }
}
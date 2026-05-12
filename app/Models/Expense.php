<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'hostel_id',
        'user_id',
        'owner_id',
        'creator_label',
        'payer_name',
        'category',
        'label',
        'amount',
        'currency',
        'expense_date',
        'note',
    ];

    protected $casts = [
        'amount'       => 'decimal:3',
        'expense_date' => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getCategoryLabelAttribute(): string
    {
        return ExpenseCategory::tryFrom($this->category)?->label() ?? $this->category;
    }

    public function getCategoryEmojiAttribute(): string
    {
        return ExpenseCategory::tryFrom($this->category)?->emoji() ?? '📌';
    }
}
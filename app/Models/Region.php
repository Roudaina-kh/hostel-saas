<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'parent_id',
        'latitude', 'longitude', 'hostels_count',
    ];

    protected function casts(): array
    {
        return [
            'latitude'      => 'float',
            'longitude'     => 'float',
            'hostels_count' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class);
    }

    /**
     * Retourne TOUS les IDs descendants + soi-même
     * CTE récursif — profondeur illimitée
     */
    public function allDescendantIds(): array
    {
        $results = DB::select("
            WITH RECURSIVE descendants AS (
                SELECT id FROM regions WHERE id = :id
                UNION ALL
                SELECT r.id FROM regions r
                INNER JOIN descendants d ON r.parent_id = d.id
            )
            SELECT id FROM descendants
        ", ['id' => $this->id]);

        return array_column($results, 'id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->parent
            ? $this->name . ', ' . $this->parent->name
            : $this->name;
    }

    public function scopeGouvernorats($query)
    {
        return $query->where('type', 'gouvernorat');
    }

    public function scopeVilles($query)
    {
        return $query->where('type', 'ville');
    }
}
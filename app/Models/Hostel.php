<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Hostel extends Model
{
protected $fillable = [
    'owner_id',
    'name',
    'type',
    'region_id',        
    'address',
    'city',
    'country',
    'phone',
    'email',
    'description',
    'latitude',
    'longitude',
    'default_currency',
    'timezone',
    'is_active',
    'status',
    'rating',
    'total_reviews',
    'cover_image',
];
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(\App\Models\Region::class);
}

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean', // ✅ ajouté
        ];
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(HostelUser::class)
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function tentSpaces()
    {
        return $this->hasMany(TentSpace::class);
    }

    public function inventoryBlocks()
    {
        return $this->hasMany(InventoryBlock::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function extraStockMovements()
    {
        return $this->hasMany(ExtraStockMovement::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
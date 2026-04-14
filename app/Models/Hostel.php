<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
    ];

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

    // ⚠️ beds() supprimé : beds n'a pas hostel_id
    // Accès via : $hostel->rooms->each->beds

    public function tentSpaces()
    {
        return $this->hasMany(TentSpace::class);
    }

    // ⚠️ taxSetting() supprimé : table dépréciée Sprint 1
    // Remplacée par taxes() ci-dessous

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
}
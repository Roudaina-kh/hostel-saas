<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function hostels()
    {
        return $this->belongsToMany(Hostel::class)
            ->using(HostelUser::class)
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers utiles
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role, int $hostelId): bool
    {
        return $this->hostels()
            ->where('hostel_id', $hostelId)
            ->wherePivot('role', $role)
            ->exists();
    }

    /**
     * Get the role for this user in a specific hostel.
     * Returns 'manager', 'staff', 'financial', or null.
     */
 /**
 * Retourne le rôle de l'utilisateur dans un hostel donné.
 * Utilisé dans le sidebar pour afficher le bon menu.
 */
public function roleInHostel(int $hostelId): ?string
{
    return $this->hostels()
        ->where('hostels.id', $hostelId)
        ->first()
        ?->pivot
        ?->role;
}
    // Mouvements de stock créés par cet utilisateur
    public function extraStockMovementsCreated()
    {
        return $this->hasMany(ExtraStockMovement::class, 'created_by');
    }

}
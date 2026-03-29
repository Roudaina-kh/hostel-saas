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

    /**
     * All hostels this user is assigned to (with pivot: role, status).
     */
    public function hostels()
    {
        return $this->belongsToMany(Hostel::class)
            ->using(HostelUser::class)
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    /**
     * Return the user's role in a given hostel.
     */
    public function roleInHostel(?int $hostelId): ?string
    {
        if (!$hostelId) return null;
        $pivot = $this->hostels()->where('hostels.id', $hostelId)->first()?->pivot;
        return $pivot?->role;
    }

    /**
     * Check if this user has an active assignment in a given hostel.
     */
    public function isActiveInHostel(?int $hostelId): bool
    {
        if (!$hostelId) return false;
        return $this->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->exists();
    }

    /**
     * Helper to get the role-based permission (backward compatibility with Manager model)
     */
    public function hasPermission(string $permission, ?int $hostelId): bool
    {
        if (!$hostelId) return false;
        $role = $this->roleInHostel($hostelId);
        
        if (!$role) return false;
        if ($role === 'manager') return true; // Les managers ont tout par défaut

        return match ($permission) {
            'can_manage_rooms'        => in_array($role, ['manager', 'staff']),
            'can_manage_reservations' => in_array($role, ['manager', 'staff']),
            'can_manage_team'         => $role === 'manager',
            'can_view_financials'     => in_array($role, ['manager', 'financial']),
            'can_manage_pricing'      => $role === 'manager',
            'can_manage_taxes'        => $role === 'manager',
            default                   => false,
        };
    }
}
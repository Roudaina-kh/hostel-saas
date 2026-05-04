<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Contrôle d'accès aux réservations selon le rôle :
 *
 * Owner   → accès complet (tous ses hostels)
 * Manager → accès complet (son hostel)
 * Staff   → accès complet (son hostel)
 * Financial → lecture seule (calendar + dashboard), pas de create/edit/store
 */
class ReservationAccess
{
    // Actions interdites aux financials
    private const FINANCIAL_BLOCKED = ['create', 'store', 'edit', 'update', 'destroy'];

    public function handle(Request $request, Closure $next, string $permission = 'view')
    {
        $role = $this->resolveRole($request);

        if ($role === null) {
            abort(403, 'Accès non autorisé.');
        }

        // Financial → accès lecture seule uniquement
        if ($role === 'financial') {
            $action = $request->route()?->getActionMethod() ?? '';
            if (in_array($action, self::FINANCIAL_BLOCKED, true)) {
                abort(403, 'Votre rôle ne vous permet pas de créer ou modifier des réservations.');
            }
        }

        return $next($request);
    }

    /**
     * Résout le rôle de l'utilisateur connecté.
     * Retourne : 'owner' | 'manager' | 'staff' | 'financial' | null
     */
    public function resolveRole(Request $request): ?string
    {
        // Owner connecté
        if (auth('owner')->check()) {
            return 'owner';
        }

        // User connecté (manager / staff / financial)
        if (auth('user')->check()) {
            $user     = auth('user')->user();
            $hostelId = session('staff_hostel_id');

            if (!$hostelId) return null;

            $pivot = $user->hostels()
                ->where('hostels.id', $hostelId)
                ->first()?->pivot;

            return $pivot?->role ?? null;
        }

        return null;
    }
}
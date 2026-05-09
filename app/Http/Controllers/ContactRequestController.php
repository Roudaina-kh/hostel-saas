<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactRequestController extends Controller
{
    /**
     * Formulaire public de demande de réservation pour un hostel donné.
     */
    public function create(Hostel $hostel)
    {
        abort_unless($hostel->is_active, 404);
        return view('contact-requests.create', compact('hostel'));
    }

    /**
     * Enregistrement de la demande publique.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'hostel_id'      => 'required|exists:hostels,id',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:150',
            'phone'          => 'nullable|string|max:30',
            'destination'    => 'required|string|max:100',
            'arrival_date'   => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'travelers'      => 'nullable|integer|min:1|max:50',
            'room_type'      => 'nullable|string|max:100',
            'message'        => 'nullable|string|max:2000',
        ], [
            'hostel_id.required'      => 'Hostel non spécifié.',
            'hostel_id.exists'        => 'Hostel introuvable.',
            'first_name.required'     => 'Le prénom est obligatoire.',
            'last_name.required'      => 'Le nom est obligatoire.',
            'email.required'          => 'L\'email est obligatoire.',
            'email.email'             => 'Format d\'email invalide.',
            'destination.required'    => 'Veuillez choisir une destination.',
            'arrival_date.required'   => 'La date d\'arrivée est obligatoire.',
            'arrival_date.after_or_equal' => 'La date d\'arrivée doit être aujourd\'hui ou plus tard.',
            'departure_date.required' => 'La date de départ est obligatoire.',
            'departure_date.after'    => 'La date de départ doit être après l\'arrivée.',
        ]);

        ContactRequest::create($data);

        return redirect()->back()->with('contact_success', true);
    }

    /**
     * Liste des demandes — filtrée par hostel courant.
     */
    public function index()
    {
        $hostelId = $this->currentHostelId();

        $base = ContactRequest::where('hostel_id', $hostelId);

        $requests = (clone $base)->latest()->paginate(20);

        $stats = [
            'total'     => (clone $base)->count(),
            'new'       => (clone $base)->where('status', 'new')->count(),
            'confirmed' => (clone $base)->where('status', 'confirmed')->count(),
            'cancelled' => (clone $base)->where('status', 'cancelled')->count(),
        ];

        return view('contact-requests.index', compact('requests', 'stats'));
    }

    /**
     * ✅ Confirmer la réservation.
     */
    public function confirm(ContactRequest $contactRequest)
    {
        $this->authorizeAccess($contactRequest);
        $contactRequest->update(['status' => 'confirmed']);
        return back()->with('success', 'Réservation confirmée.');
    }

    /**
     * ❌ Annuler la demande.
     */
    public function cancel(ContactRequest $contactRequest)
    {
        $this->authorizeAccess($contactRequest);
        $contactRequest->update(['status' => 'cancelled']);
        return back()->with('success', 'Demande annulée.');
    }

    /**
     * 🗑 Supprimer.
     */
    public function destroy(ContactRequest $contactRequest)
    {
        $this->authorizeAccess($contactRequest);
        $contactRequest->delete();
        return back()->with('success', 'Demande supprimée.');
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers privés (filtrage + sécurité par hostel)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Récupère l'ID du hostel actuellement sélectionné selon le guard.
     */
    private function currentHostelId(): int
    {
        if (Auth::guard('owner')->check()) {
            $id = (int) session('hostel_id');
            abort_if(!$id, 403, 'Aucun hostel sélectionné.');
            return $id;
        }

        if (Auth::guard('user')->check()) {
            $id = (int) session('staff_hostel_id');
            abort_if(!$id, 403, 'Aucun hostel sélectionné.');
            return $id;
        }

        abort(403, 'Authentification requise.');
    }

    /**
     * Empêche un owner/manager de modifier la demande d'un autre hostel.
     */
    private function authorizeAccess(ContactRequest $contactRequest): void
    {
        abort_unless(
            $contactRequest->hostel_id === $this->currentHostelId(),
            403,
            'Vous n\'avez pas accès à cette demande.'
        );
    }
}
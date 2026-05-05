<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    /**
     * Enregistrement depuis le formulaire public de la landing page.
     * Validation stricte côté serveur — aucun champ requis vide.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
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
     * Liste des demandes — accessible owner et manager.
     */
    public function index()
    {
        $requests = ContactRequest::latest()->paginate(20);

        $stats = [
            'total'   => ContactRequest::count(),
            'new'     => ContactRequest::where('status', 'new')->count(),
            'read'    => ContactRequest::where('status', 'read')->count(),
            'replied' => ContactRequest::where('status', 'replied')->count(),
        ];

        return view('contact-requests.index', compact('requests', 'stats'));
    }

    /**
     * Marquer comme lu.
     */
    public function markRead(ContactRequest $contactRequest)
    {
        $contactRequest->update(['status' => 'read']);
        return back()->with('success', 'Demande marquée comme lue.');
    }

    /**
     * Marquer comme répondu.
     */
    public function markReplied(ContactRequest $contactRequest)
    {
        $contactRequest->update(['status' => 'replied']);
        return back()->with('success', 'Demande marquée comme répondue.');
    }

    /**
     * Supprimer.
     */
    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();
        return back()->with('success', 'Demande supprimée.');
    }
}
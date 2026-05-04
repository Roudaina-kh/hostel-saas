<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ManagerPaymentController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }

    private function authorizePayment(Payment $payment): void
    {
        abort_unless(
            $payment->reservation->hostel_id === (int) $this->hostelId(),
            403,
            'Accès non autorisé à ce paiement.'
        );
    }

    private function authorizeReservation(Reservation $reservation): void
    {
        abort_unless(
            $reservation->hostel_id === (int) $this->hostelId(),
            403
        );
    }

    public function index()
    {
        $hostelId = $this->hostelId();

        $payments = Payment::whereHas('reservation', fn ($q) =>
                $q->where('hostel_id', $hostelId)
            )
            ->with(['reservation', 'reservationPerson', 'user'])
            ->latest()
            ->paginate(20);

        $totalTnd = Payment::whereHas('reservation', fn ($q) =>
                $q->where('hostel_id', $hostelId)
            )
            ->where('status', 'paid')
            ->sum('amount_tnd');

        return view('payments.index', compact('payments', 'totalTnd'));
    }

    public function create()
    {
        $hostelId = $this->hostelId();

        $preselectedReservation = null;
        if ($reservationId = request()->integer('reservation_id')) {
            $preselectedReservation = Reservation::where('id', $reservationId)
                ->where('hostel_id', $hostelId)
                ->first();
        }

        $reservations = Reservation::where('hostel_id', $hostelId)
        ->whereDoesntHave('payments', fn($q) => $q->where('status', 'paid'))
        ->latest()
        ->get(['id', 'guest_name', 'start_date', 'end_date']);

        return view('payments.create', compact('reservations', 'preselectedReservation'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['amount_tnd'] = $request->amountTnd();
        $data['user_id']    = Auth::guard('user')->id();
        $data['reservation_person_id'] = $data['reservation_person_id'] ?: null;

        Payment::create($data);

        return redirect()
            ->route('manager.payments.index')
            ->with('success', 'Paiement enregistré.');
    }

    public function show(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->load(['reservation', 'reservationPerson', 'user']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->load(['reservation', 'reservationPerson']);

        return view('payments.edit', compact('payment'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $this->authorizePayment($payment);

        $data = $request->validated();
        $data['amount_tnd'] = $request->amountTnd();
        unset($data['reservation_id'], $data['reservation_person_id']);

        $payment->update($data);

        return redirect()
            ->route('manager.payments.show', $payment)
            ->with('success', 'Paiement mis à jour.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->delete();

        return redirect()
            ->route('manager.payments.index')
            ->with('success', 'Paiement supprimé.');
    }

    public function people(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $people = collect();
        if (method_exists($reservation, 'people')) {
            $people = $reservation->people()->get(['id', 'first_name', 'last_name']);
        } elseif (method_exists($reservation, 'reservationPeople')) {
            $people = $reservation->reservationPeople()->get(['id', 'first_name', 'last_name']);
        }

        return response()->json($people);
    }
}
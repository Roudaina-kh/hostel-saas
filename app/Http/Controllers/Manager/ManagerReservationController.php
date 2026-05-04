<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ExchangeRate;
use App\Models\Guest;
use App\Models\Hostel;
use App\Models\Reservation;
use App\Models\ReservationPerson;
use App\Models\Room;
use App\Models\TentSpace;
use App\Services\Reservation\AvailabilityService;
use App\Services\Reservation\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Gère les réservations pour Manager et Staff (guard : auth:user)
 *
 * FIX 2 : "Ajouté par" est automatiquement l'utilisateur connecté.
 *         Rôle financial → lecture seule (create/store/edit/update bloqués).
 */
class ManagerReservationController extends Controller
{
    public function __construct(
        private AvailabilityService $availabilityService,
        private PricingService      $pricingService,
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function currentHostel(): Hostel
    {
        $hostelId = session('staff_hostel_id');
        abort_if(!$hostelId, 403, 'Aucun hostel sélectionné.');
        return Hostel::findOrFail($hostelId);
    }

    private function currentRole(): string
    {
        $user     = auth('user')->user();
        $hostelId = session('staff_hostel_id');
        if (!$user || !$hostelId) return 'unknown';
        return $user->hostels()->where('hostels.id', $hostelId)->first()?->pivot->role ?? 'unknown';
    }

    private function denyFinancial(): void
    {
        if ($this->currentRole() === 'financial') {
            abort(403, 'Votre rôle ne vous permet pas de créer ou modifier des réservations.');
        }
    }

    private function getRoutePrefix(): string
    {
        $routeName = request()->route()?->getName() ?? '';
        return str_starts_with($routeName, 'manager.') ? 'manager' : 'staff';
    }

    private function formData(Hostel $hostel): array
    {
        $rooms = Room::with(['beds' => fn ($q) => $q->where('is_enabled', true)])
            ->where('hostel_id', $hostel->id)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        $tentSpaces = TentSpace::where('hostel_id', $hostel->id)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        $countries = Country::orderBy('name')->get();

        $rawRates = ExchangeRate::latest()->get()->unique('currency');
        $rates    = $rawRates->mapWithKeys(function ($rate) {
            $currency = strtoupper($rate->currency ?? '');
            return [
                $currency => (object) [
                    'currency'         => $currency,
                    'sell_rate_to_tnd' => $this->extractSellRate($rate),
                    'buy_rate_to_tnd'  => $this->extractBuyRate($rate),
                ],
            ];
        });

        $members = $hostel->users()->orderBy('name')->get();

        // ── FIX 2 : utilisateur connecté automatique ──────────────────────
        $user = auth('user')->user();
        $role = $this->currentRole();
        $roleLabel = match($role) {
            'manager'   => 'Manager',
            'staff'     => 'Staff',
            'financial' => 'Financier',
            default     => ucfirst($role),
        };
        $currentUser = [
            'id'   => $user->id,
            'name' => $user->name,
            'role' => $roleLabel,
        ];

        $prefix = $this->getRoutePrefix();
        $routes = [
            'index'  => $prefix . '.reservations.index',
            'create' => $prefix . '.reservations.create',
            'store'  => $prefix . '.reservations.store',
            'edit'   => $prefix . '.reservations.edit',
            'update' => $prefix . '.reservations.update',
            'avail'  => $prefix . '.reservations.available-units',
            'pwd'    => $prefix . '.reservations.check-password',
        ];

        return compact('hostel', 'rooms', 'tentSpaces', 'countries', 'rates', 'members', 'currentUser', 'routes');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $hostel = $this->currentHostel();
        $role   = $this->currentRole();
        $year   = (int) $request->get('year', now()->year);

        $reservations = Reservation::with(['mainGuest', 'people'])
            ->where('hostel_id', $hostel->id)
            ->whereYear('start_date', $year)
            ->orderByDesc('start_date')
            ->get();

        $stats = [
            'total'     => Reservation::where('hostel_id', $hostel->id)->count(),
            'confirmed' => Reservation::where('hostel_id', $hostel->id)->where('status', 'confirmed')->count(),
            'pending'   => Reservation::where('hostel_id', $hostel->id)->where('status', 'pending')->count(),
            'revenue'   => Reservation::where('hostel_id', $hostel->id)->whereNotIn('status', ['cancelled'])->sum('total_price_tnd'),
        ];

        $calendarDays = [];
        foreach ($reservations as $res) {
            if ($res->status === 'cancelled') continue;
            $start = \Carbon\Carbon::parse($res->start_date);
            $end   = \Carbon\Carbon::parse($res->end_date);
            $cur   = $start->copy();
            while ($cur->lt($end)) {
                $key = $cur->format('Y-m-d');
                if (!isset($calendarDays[$key]) || $res->status === 'confirmed') {
                    $calendarDays[$key] = $res->status;
                }
                $cur->addDay();
            }
        }

        $canCreate = $role !== 'financial';
        $canEdit   = $role !== 'financial';

        $data = $this->formData($hostel);

        return view('reservations.index', array_merge($data, compact(
            'reservations', 'stats', 'calendarDays', 'year', 'role', 'canCreate', 'canEdit',
        )));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────────────────────

    public function create()
    {
        $this->denyFinancial();
        $hostel = $this->currentHostel();
        return view('reservations.create', $this->formData($hostel));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE — FIX 2 : user connecté détecté automatiquement
    // ─────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $this->denyFinancial();
        $hostel      = $this->currentHostel();
        $user        = auth('user')->user();
        $routePrefix = $this->getRoutePrefix();

        $request->validate([
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'nights'      => ['required', 'integer', 'min:1'],
            'total_guests'=> ['required', 'integer', 'min:1'],
            'status'      => ['required', 'in:pending,confirmed'],
            'source'      => ['nullable', 'string', 'max:100'],
            'notes'       => ['nullable', 'string', 'max:2000'],
            'password'    => ['required', 'string'],
            'guests_data' => ['required', 'json'],
        ]);

        // ── FIX 2 : vérifier le mot de passe de l'utilisateur connecté ────
        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $guestsData = json_decode($request->guests_data, true);
        if (!is_array($guestsData) || count($guestsData) < 1) {
            return back()->withInput()->withErrors(['guests_data' => 'Données guests invalides.']);
        }

        DB::beginTransaction();
        try {
            $this->validateGuestData($guestsData[0]);
            $mainGuest = $this->createGuest($guestsData[0]);

            $roleLabel = match($this->currentRole()) {
                'manager'   => 'Manager',
                'staff'     => 'Staff',
                default     => ucfirst($this->currentRole()),
            };

            $reservation = Reservation::create([
                'hostel_id'       => $hostel->id,
                'main_guest_id'   => $mainGuest->id,
                'start_date'      => $request->start_date,
                'end_date'        => $request->end_date,
                'nights'          => (int) $request->nights,
                'total_guests'    => count($guestsData),
                'status'          => $request->status,
                'source'          => $request->source,
                'total_price_tnd' => 0,
                'total_price_eur' => null,
                'total_price_usd' => null,
                'notes'           => $request->notes,
                'created_by'      => $user->name . ' (' . $roleLabel . ')',  // ex: "Ahmed (Manager)"
                'user_id'         => $user->id,
            ]);

            foreach ($guestsData as $guestData) {
                $this->validateGuestData($guestData);
                $guest    = $this->createGuest($guestData);
                $itemType = $guestData['item_type'];
                $itemId   = (int) $guestData['item_id'];

                // ── FIX 1 : vérification disponibilité stricte (bed = 1 personne) ──
                if (!$this->availabilityService->isAvailable(
                    $hostel->id, $itemType, $itemId, $request->start_date, $request->end_date,
                )) {
                    throw new \Exception(
                        "L'unité sélectionnée pour « {$guest->first_name} {$guest->last_name} » n'est plus disponible pour ces dates."
                    );
                }

                $priceInput   = (float) $guestData['price_input'];
                $currency     = strtoupper($guestData['currency']);
                $exchangeRate = (float) $guestData['exchange_rate'];
                $priceTnd     = $this->pricingService->computePriceTnd($priceInput, $currency, $exchangeRate);

                ReservationPerson::create([
                    'reservation_id' => $reservation->id,
                    'guest_id'       => $guest->id,
                    'display_name'   => trim($guest->first_name . ' ' . $guest->last_name),
                    'item_type'      => $itemType,
                    'item_id'        => $itemId,
                    'price_tnd'      => $priceTnd,
                    'price_input'    => $priceInput,
                    'currency'       => $currency,
                    'exchange_rate'  => $exchangeRate,
                    'is_checked_in'  => false,
                ]);
            }

            $totals = $this->pricingService->computeTotals($guestsData);
            $reservation->update($totals);
            DB::commit();

            return redirect()->route($routePrefix . '.reservations.index')
                ->with('success', '✅ Réservation créée avec succès !');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $this->denyFinancial();
        $hostel      = $this->currentHostel();
        $reservation = Reservation::where('hostel_id', $hostel->id)->findOrFail($id);

        $existingGuests = $reservation->people->map(function ($person) {
            $guest = $person->guest;
            return [
                'first_name'    => $guest?->first_name    ?? '',
                'last_name'     => $guest?->last_name     ?? '',
                'identity_card' => $guest?->identity_card ?? '',
                'email'         => $guest?->email         ?? '',
                'phone'         => $guest?->phone         ?? '',
                'country_id'    => $guest?->country_id    ?? '',
                'gender'        => $guest?->gender        ?? 'male',
                'item_type'     => $person->item_type,
                'item_id'       => $person->item_id,
                'price_input'   => $person->price_input,
                'currency'      => $person->currency,
                'price_tnd'     => $person->price_tnd,
                'exchange_rate' => $person->exchange_rate,
            ];
        })->values()->toArray();

        $data = $this->formData($hostel);
        $data['reservation']    = $reservation;
        $data['existingGuests'] = $existingGuests;

        return view('reservations.edit', $data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // UPDATE — FIX 2 : user connecté détecté automatiquement
    // ─────────────────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $this->denyFinancial();
        $hostel      = $this->currentHostel();
        $reservation = Reservation::where('hostel_id', $hostel->id)->findOrFail($id);
        $user        = auth('user')->user();
        $routePrefix = $this->getRoutePrefix();

        $request->validate([
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'nights'      => ['required', 'integer', 'min:1'],
            'total_guests'=> ['required', 'integer', 'min:1'],
            'status'      => ['required', 'in:pending,confirmed,cancelled'],
            'source'      => ['nullable', 'string', 'max:100'],
            'notes'       => ['nullable', 'string', 'max:2000'],
            'password'    => ['required', 'string'],
            'guests_data' => ['required', 'json'],
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $guestsData = json_decode($request->guests_data, true);
        if (!is_array($guestsData) || count($guestsData) < 1) {
            return back()->withInput()->withErrors(['guests_data' => 'Données guests invalides.']);
        }

        DB::beginTransaction();
        try {
            $reservation->people()->delete();

            $this->validateGuestData($guestsData[0]);
            $mainGuest = $this->createGuest($guestsData[0]);

            $roleLabel = match($this->currentRole()) {
                'manager' => 'Manager',
                'staff'   => 'Staff',
                default   => ucfirst($this->currentRole()),
            };

            $reservation->update([
                'main_guest_id' => $mainGuest->id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'nights'        => (int) $request->nights,
                'total_guests'  => count($guestsData),
                'status'        => $request->status,
                'source'        => $request->source,
                'notes'         => $request->notes,
                'created_by'    => $user->name . ' (' . $roleLabel . ')',
                'user_id'       => $user->id,
            ]);

            foreach ($guestsData as $guestData) {
                $this->validateGuestData($guestData);
                $guest    = $this->createGuest($guestData);
                $itemType = $guestData['item_type'];
                $itemId   = (int) $guestData['item_id'];

                if (!$this->availabilityService->isAvailable(
                    $hostel->id, $itemType, $itemId,
                    $request->start_date, $request->end_date, $reservation->id,
                )) {
                    throw new \Exception("L'unité de « {$guest->first_name} {$guest->last_name} » n'est plus disponible.");
                }

                $priceInput   = (float) $guestData['price_input'];
                $currency     = strtoupper($guestData['currency']);
                $exchangeRate = (float) $guestData['exchange_rate'];
                $priceTnd     = $this->pricingService->computePriceTnd($priceInput, $currency, $exchangeRate);

                ReservationPerson::create([
                    'reservation_id' => $reservation->id,
                    'guest_id'       => $guest->id,
                    'display_name'   => trim($guest->first_name . ' ' . $guest->last_name),
                    'item_type'      => $itemType,
                    'item_id'        => $itemId,
                    'price_tnd'      => $priceTnd,
                    'price_input'    => $priceInput,
                    'currency'       => $currency,
                    'exchange_rate'  => $exchangeRate,
                    'is_checked_in'  => false,
                ]);
            }

            $totals = $this->pricingService->computeTotals($guestsData);
            $reservation->update($totals);
            DB::commit();

            return redirect()->route($routePrefix . '.reservations.index')
                ->with('success', '✅ Réservation modifiée avec succès !');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX availableUnits
    // ─────────────────────────────────────────────────────────────────────────

    public function availableUnits(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ]);
        $hostel = $this->currentHostel();
        return response()->json(
            $this->availabilityService->getAvailableUnits($hostel->id, $request->start_date, $request->end_date)
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX checkPassword — FIX 2 : vérifie l'utilisateur connecté directement
    // ─────────────────────────────────────────────────────────────────────────

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);
        $user    = auth('user')->user();
        $success = Hash::check($request->password, $user->password);
        return response()->json(['success' => $success]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers privés
    // ─────────────────────────────────────────────────────────────────────────

    private function extractSellRate(ExchangeRate $rate): float
    {
        foreach (['sell_rate_to_tnd','sell_rate','rate_sell','sell','rate'] as $col) {
            if (isset($rate->$col) && $rate->$col > 0) return (float) $rate->$col;
        }
        return 0.0;
    }

    private function extractBuyRate(ExchangeRate $rate): float
    {
        foreach (['buy_rate_to_tnd','buy_rate','rate_buy','buy','rate'] as $col) {
            if (isset($rate->$col) && $rate->$col > 0) return (float) $rate->$col;
        }
        return 0.0;
    }

    private function createGuest(array $data): Guest
    {
        return Guest::create([
            'first_name'    => trim($data['first_name']),
            'last_name'     => trim($data['last_name']),
            'identity_card' => $data['identity_card'] ?? null,
            'email'         => !empty($data['email']) ? $data['email'] : null,
            'phone'         => !empty($data['phone']) ? $data['phone'] : null,
            'country_id'    => (int) $data['country_id'],
            'gender'        => $data['gender'],
        ]);
    }

    private function validateGuestData(array $data): void
    {
        $required = ['first_name','last_name','country_id','gender','item_type','item_id','price_input','currency','exchange_rate'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || (string) $data[$field] === '') {
                throw new \InvalidArgumentException("Champ manquant pour un guest : {$field}");
            }
        }
        if (!in_array($data['item_type'], ['bed','room','tent_space'], true))
            throw new \InvalidArgumentException("Type d'affectation invalide.");
        if (!in_array(strtoupper($data['currency']), ['TND','EUR','USD'], true))
            throw new \InvalidArgumentException("Devise invalide.");
        if ((float) $data['price_input'] < 0)
            throw new \InvalidArgumentException('Le prix ne peut pas être négatif.');
        if ((int) $data['item_id'] <= 0)
            throw new \InvalidArgumentException('Une unité doit être sélectionnée pour chaque guest.');
    }
    // ─────────────────────────────────────────────────────────────────────────
    // DESTROY — Suppression réservation (Manager / Staff)
    // ─────────────────────────────────────────────────────────────────────────

    public function destroy(Request $request, int $id)
    {
        $this->denyFinancial();
        $hostel      = $this->currentHostel();
        $reservation = Reservation::where('hostel_id', $hostel->id)->findOrFail($id);
        $user        = auth('user')->user();
        $routePrefix = $this->getRoutePrefix();

        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        DB::beginTransaction();
        try {
            $reservation->people()->delete();
            $reservation->delete();
            DB::commit();

            return redirect()->route($routePrefix . '.reservations.index')
                ->with('success', '🗑️ Réservation #' . $id . ' supprimée avec succès.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

}
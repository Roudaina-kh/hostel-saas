<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Models\Country;
use App\Models\Extra;
use App\Models\ExchangeRate;
use App\Models\Guest;
use App\Models\Hostel;
use App\Models\Reservation;
use App\Models\ReservationExtra;
use App\Models\ReservationPerson;
use App\Models\Room;
use App\Models\TentSpace;
use App\Models\User;
use App\Services\Reservation\AvailabilityService;
use App\Services\Reservation\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateReservationController extends Controller
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
        $hostelId = session('hostel_id');
        abort_if(!$hostelId, 403, 'Aucun hostel sélectionné.');

        $hostel = auth('owner')->user()
            ->hostels()
            ->where('hostels.id', $hostelId)
            ->first();

        abort_if(!$hostel, 403, 'Accès non autorisé à ce hostel.');

        return $hostel;
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

        $extras = Extra::where('hostel_id', $hostel->id)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        $owner = auth('owner')->user();
        $currentUser = [
            'id'   => null,
            'name' => $owner->name,
            'role' => 'Propriétaire',
        ];

        $routes = [
            'index'          => 'reservations.index',
            'create'         => 'reservations.create',
            'store'          => 'reservations.store',
            'edit'           => 'reservations.edit',
            'update'         => 'reservations.update',
            'destroy'        => 'reservations.destroy',
            'avail'          => 'reservations.available-units',
            'pwd'            => 'reservations.check-password',
            'payment_create' => 'payments.create',     // ← NEW
            'store_url'      => route('reservations.store'),
            'index_url'      => route('reservations.index'),
            'avail_url'      => route('reservations.available-units'),
            'pwd_url'        => route('reservations.check-password'),
        ];

        return compact('hostel', 'rooms', 'tentSpaces', 'countries', 'rates', 'members', 'currentUser', 'routes', 'extras');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 🆕 PLANNING DATA BUILDER
    // ─────────────────────────────────────────────────────────────────────────

    private function buildPlanningData(Hostel $hostel, Request $request): array
    {
        // Période
        $startParam = $request->get('planning_start');
        $start = $startParam ? \Carbon\Carbon::parse($startParam) : \Carbon\Carbon::today();

        $days = (int) $request->get('planning_days', 14);
        $days = in_array($days, [7, 14, 30]) ? $days : 14;

        $end = $start->copy()->addDays($days - 1);

        // Liste des jours
        $dates = [];
        $cur = $start->copy();
        while ($cur->lte($end)) {
            $dates[] = $cur->copy();
            $cur->addDay();
        }

        // Unités
        $privateRooms = Room::where('hostel_id', $hostel->id)
            ->where('type', 'private')
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        $dormitoryRooms = Room::where('hostel_id', $hostel->id)
            ->where('type', 'dormitory')
            ->where('is_enabled', true)
            ->with(['beds' => fn($q) => $q->where('is_enabled', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        $tentSpaces = TentSpace::where('hostel_id', $hostel->id)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        // Réservations chevauchantes (non annulées)
        $reservations = Reservation::where('hostel_id', $hostel->id)
            ->whereNotIn('status', ['cancelled'])
            ->where('start_date', '<=', $end->toDateString())
            ->where('end_date',   '>=', $start->toDateString())
            ->with(['people', 'mainGuest'])
            ->get();

        // Index d'occupation : occupancy[item_type][item_id][date] = ['guest' => ..., 'status' => ...]
        $occupancy = [];
        foreach ($reservations as $res) {
            $resStart = \Carbon\Carbon::parse($res->start_date);
            $resEnd   = \Carbon\Carbon::parse($res->end_date);

            foreach ($res->people as $person) {
                $cur = $resStart->copy();
                while ($cur->lt($resEnd)) {
                    if ($cur->gte($start) && $cur->lte($end)) {
                        $key = $cur->format('Y-m-d');
                        $occupancy[$person->item_type][$person->item_id][$key] = [
                            'guest_name'     => $person->display_name ?: ($res->mainGuest?->first_name . ' ' . $res->mainGuest?->last_name),
                            'status'         => $res->status,
                            'reservation_id' => $res->id,
                        ];
                    }
                    $cur->addDay();
                }
            }
        }

        return [
            'start'           => $start,
            'end'             => $end,
            'days'            => $days,
            'dates'           => $dates,
            'private_rooms'   => $privateRooms,
            'dormitory_rooms' => $dormitoryRooms,
            'tent_spaces'     => $tentSpaces,
            'occupancy'       => $occupancy,
            'show_beds'       => (bool) $request->boolean('show_beds', false),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $hostel = $this->currentHostel();
        $role   = 'owner';
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

        // 🆕 Planning data
        $planning = $this->buildPlanningData($hostel, $request);

        $canCreate = true;
        $canEdit   = true;
        $data      = $this->formData($hostel);

        return view('reservations.index', array_merge($data, compact(
            'reservations', 'stats', 'year', 'role', 'canCreate', 'canEdit', 'planning',
        )));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────────────────────

    public function create()
    {
        $hostel = $this->currentHostel();
        return view('reservations.create', $this->formData($hostel));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE (inchangé)
    // ─────────────────────────────────────────────────────────────────────────

    public function store(StoreReservationRequest $request)
    {
        $hostel = $this->currentHostel();
        $owner  = auth('owner')->user();

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $guestsData = json_decode($request->guests_data, true);
        if (!is_array($guestsData) || count($guestsData) < 1) {
            return back()->withInput()->withErrors(['guests_data' => 'Données guests invalides.']);
        }

        $extrasData = [];
        if ($request->filled('extras_data')) {
            $extrasData = json_decode($request->extras_data, true) ?? [];
        }

        DB::beginTransaction();
        try {
            $this->validateGuestData($guestsData[0]);
            $mainGuest = $this->createGuest($guestsData[0]);

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
                'extras_total_tnd'=> 0,
                'notes'           => $request->notes,
                'created_by'      => $owner->name,
                'user_id'         => null,
            ]);

            foreach ($guestsData as $guestData) {
                $this->validateGuestData($guestData);
                $guest    = $this->createGuest($guestData);
                $itemType = $guestData['item_type'];
                $itemId   = (int) $guestData['item_id'];

                if (!$this->availabilityService->isAvailable(
                    $hostel->id, $itemType, $itemId, $request->start_date, $request->end_date,
                )) {
                    throw new \Exception(
                        "L'unité sélectionnée pour « {$guest->first_name} {$guest->last_name} » n'est plus disponible."
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

            $extrasTotalTnd = 0;
            foreach ($extrasData as $ed) {
                $extraId  = (int) ($ed['extra_id'] ?? 0);
                $quantity = (int) ($ed['quantity'] ?? 0);
                if ($extraId <= 0 || $quantity <= 0) continue;

                $extra = Extra::where('id', $extraId)
                    ->where('hostel_id', $hostel->id)
                    ->where('is_enabled', true)
                    ->first();

                if (!$extra) continue;

                if ($extra->hasTrackedStock() && $extra->stock_quantity < $quantity) {
                    throw new \Exception(
                        "Stock insuffisant pour l'extra « {$extra->name} » (disponible : {$extra->stock_quantity})."
                    );
                }

                $priceTnd = 0;
                $price = $extra->prices()->first();
                if ($price) {
                    $priceTnd = (float) $price->price_ttc * $quantity;
                }

                ReservationExtra::create([
                    'reservation_id' => $reservation->id,
                    'extra_id'       => $extra->id,
                    'quantity'       => $quantity,
                    'price_tnd'      => $priceTnd,
                ]);

                if ($extra->hasTrackedStock()) {
                    $extra->decrement('stock_quantity', $quantity);
                }

                $extrasTotalTnd += $priceTnd;
            }

            $totals = $this->pricingService->computeTotals($guestsData);
            $totals['extras_total_tnd'] = $extrasTotalTnd;
            $totals['total_price_tnd']  = ($totals['total_price_tnd'] ?? 0) + $extrasTotalTnd;
            $reservation->update($totals);

            DB::commit();

            return redirect()->route('reservations.index')
                ->with('success', '✅ Réservation créée avec succès !');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT, UPDATE, AJAX, DESTROY, helpers — inchangés (copie ton fichier actuel)
    // ─────────────────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
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

        $existingExtras = $reservation->extras->mapWithKeys(function ($re) {
            return [$re->extra_id => $re->quantity];
        })->toArray();

        $data = $this->formData($hostel);
        $data['reservation']    = $reservation;
        $data['existingGuests'] = $existingGuests;
        $data['existingExtras'] = $existingExtras;

        return view('reservations.edit', $data);
    }

    public function update(Request $request, int $id)
    {
        $hostel      = $this->currentHostel();
        $reservation = Reservation::where('hostel_id', $hostel->id)->findOrFail($id);
        $owner       = auth('owner')->user();

        $request->validate([
            'start_date'   => ['required', 'date'],
            'end_date'     => ['required', 'date', 'after:start_date'],
            'nights'       => ['required', 'integer', 'min:1'],
            'total_guests' => ['required', 'integer', 'min:1'],
            'status'       => ['required', 'in:pending,confirmed,cancelled'],
            'source'       => ['nullable', 'string', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'password'     => ['required', 'string'],
            'guests_data'  => ['required', 'json'],
        ]);

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $guestsData = json_decode($request->guests_data, true);
        if (!is_array($guestsData) || count($guestsData) < 1) {
            return back()->withInput()->withErrors(['guests_data' => 'Données guests invalides.']);
        }

        $extrasData = [];
        if ($request->filled('extras_data')) {
            $extrasData = json_decode($request->extras_data, true) ?? [];
        }

        DB::beginTransaction();
        try {
            foreach ($reservation->extras as $oldExtra) {
                if ($oldExtra->extra && $oldExtra->extra->hasTrackedStock()) {
                    $oldExtra->extra->increment('stock_quantity', $oldExtra->quantity);
                }
            }
            $reservation->extras()->delete();
            $reservation->people()->delete();

            $this->validateGuestData($guestsData[0]);
            $mainGuest = $this->createGuest($guestsData[0]);

            $reservation->update([
                'main_guest_id' => $mainGuest->id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'nights'        => (int) $request->nights,
                'total_guests'  => count($guestsData),
                'status'        => $request->status,
                'source'        => $request->source,
                'notes'         => $request->notes,
                'created_by'    => $owner->name,
                'user_id'       => null,
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

            $extrasTotalTnd = 0;
            foreach ($extrasData as $ed) {
                $extraId  = (int) ($ed['extra_id'] ?? 0);
                $quantity = (int) ($ed['quantity'] ?? 0);
                if ($extraId <= 0 || $quantity <= 0) continue;

                $extra = Extra::where('id', $extraId)
                    ->where('hostel_id', $hostel->id)
                    ->where('is_enabled', true)
                    ->first();
                if (!$extra) continue;

                if ($extra->hasTrackedStock() && $extra->stock_quantity < $quantity) {
                    throw new \Exception("Stock insuffisant pour « {$extra->name} » (disponible : {$extra->stock_quantity}).");
                }

                $priceTnd = 0;
                $price = $extra->prices()->first();
                if ($price) {
                    $priceTnd = (float) $price->price_ttc * $quantity;
                }

                ReservationExtra::create([
                    'reservation_id' => $reservation->id,
                    'extra_id'       => $extra->id,
                    'quantity'       => $quantity,
                    'price_tnd'      => $priceTnd,
                ]);

                if ($extra->hasTrackedStock()) {
                    $extra->decrement('stock_quantity', $quantity);
                }

                $extrasTotalTnd += $priceTnd;
            }

            $totals = $this->pricingService->computeTotals($guestsData);
            $totals['extras_total_tnd'] = $extrasTotalTnd;
            $totals['total_price_tnd']  = ($totals['total_price_tnd'] ?? 0) + $extrasTotalTnd;
            $reservation->update($totals);

            DB::commit();

            return redirect()->route('reservations.index')
                ->with('success', '✅ Réservation modifiée avec succès !');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

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

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);
        $owner   = auth('owner')->user();
        $success = Hash::check($request->password, $owner->password);
        return response()->json(['success' => $success]);
    }

    public function destroy(Request $request, int $id)
    {
        $hostel      = $this->currentHostel();
        $reservation = Reservation::where('hostel_id', $hostel->id)->findOrFail($id);
        $owner       = auth('owner')->user();

        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        DB::beginTransaction();
        try {
            foreach ($reservation->extras as $re) {
                if ($re->extra && $re->extra->hasTrackedStock()) {
                    $re->extra->increment('stock_quantity', $re->quantity);
                }
            }
            $reservation->extras()->delete();
            $reservation->people()->delete();
            $reservation->delete();
            DB::commit();

            return redirect()->route('reservations.index')
                ->with('success', '🗑️ Réservation #' . $id . ' supprimée avec succès.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }

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
            throw new \InvalidArgumentException("Type d'affectation invalide : {$data['item_type']}");
        if (!in_array(strtoupper($data['currency']), ['TND','EUR','USD'], true))
            throw new \InvalidArgumentException("Devise invalide : {$data['currency']}");
        if ((float) $data['price_input'] < 0)
            throw new \InvalidArgumentException('Le prix ne peut pas être négatif.');
        if ((int) $data['item_id'] <= 0)
            throw new \InvalidArgumentException('Une unité doit être sélectionnée pour chaque guest.');
    }
}
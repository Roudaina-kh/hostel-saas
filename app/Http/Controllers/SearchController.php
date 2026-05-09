<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Services\Search\AvailabilityService;
use App\Services\Search\SearchParams;
use App\Services\Search\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private SearchService       $search,
        private AvailabilityService $availability,
    ) {}

    /**
     * Page de recherche / résultats
     */
    public function index(Request $request)
    {
        $params  = SearchParams::fromRequest($request);
        $result  = null;
        $popular = null;

        if ($params->hasSearch()) {
            $result = $this->search->search($params);
        } else {
            // Page d'accueil → hostels populaires
            $popular = $this->search->featured(6);
        }

        $popularRegions = Region::gouvernorats()
            ->withCount('hostels')
            ->having('hostels_count', '>', 0)
            ->orderByDesc('hostels_count')
            ->limit(6)
            ->get();

        return view('search.index', compact(
            'result', 'params', 'popular', 'popularRegions'
        ));
    }

    /**
     * AJAX — autocomplete régions
     * GET /search/regions?q=tunis
     */
    public function regions(Request $request)
{
    $q = $request->input('q', '');

    $regions = Region::when($q, fn($query) =>
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%") // ✅ chercher aussi par slug
        )
        ->orderByRaw("FIELD(type, 'gouvernorat', 'ville', 'zone')")
        ->limit(10)
        ->get(['id', 'name', 'slug', 'type']);

    return response()->json($regions);
}
    /**
     * AJAX — disponibilité d'un hostel
     * GET /search/availability?hostel_id=1&check_in=...&check_out=...
     */
    public function availability(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $avail = $this->availability->getHostelAvailability(
            (int) $request->hostel_id,
            $request->check_in,
            $request->check_out
        );

        return response()->json($avail);
    }

    /**
     * Fiche détail hostel (public)
     * GET /explore/{hostel}
     */
    public function show(int $id, Request $request)
    {
        $hostel = \App\Models\Hostel::where('is_active', true)
            ->with(['region', 'rooms.beds', 'prices', 'extras'])
            ->findOrFail($id);

        $availability = null;
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $availability = $this->availability->getHostelAvailability(
                $hostel->id,
                $request->check_in,
                $request->check_out
            );
        }

        return view('search.show', compact('hostel', 'availability'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Region;
use App\Services\Search\AvailabilityService;
use App\Services\Search\SearchParams;
use App\Services\Search\SearchService;
use Illuminate\Http\Request;

/**
 * SearchController — sert 2 routes publiques :
 *   GET /         →  landing()  → page d'accueil (6 populaires + carte)
 *   GET /search   →  index()    → page résultats (paginée, sans carte)
 *
 * Pattern : Single Responsibility Principle — chaque méthode son rôle,
 * helpers privés mutualisés (DRY).
 */
class SearchController extends Controller
{
    public function __construct(
        private SearchService       $search,
        private AvailabilityService $availability,
    ) {}

    /**
     * 🏠 Page d'accueil — Route GET /
     */
    public function landing(Request $request)
    {
        $params         = SearchParams::fromRequest($request);
        $popular        = $this->search->featured(6);
        $result         = null;
        $popularRegions = $this->getPopularRegions();
        $mapHostels     = $this->getMapHostels(); // ✨ NEW : data pour la carte

        return view('search.index', compact(
            'result', 'params', 'popular', 'popularRegions', 'mapHostels'
        ));
    }

    /**
     * 🔍 Résultats paginés — Route GET /search
     */
    public function index(Request $request)
    {
        $params         = SearchParams::fromRequest($request);
        $result         = $this->search->search($params);
        $popular        = null;
        $popularRegions = $this->getPopularRegions();
        $mapHostels     = null; // pas de carte sur /search (carte = teaser landing only)

        return view('search.index', compact(
            'result', 'params', 'popular', 'popularRegions', 'mapHostels'
        ));
    }

    /**
     * AJAX — autocomplete régions
     */
    public function regions(Request $request)
    {
        $q = $request->input('q', '');

        $regions = Region::when($q, fn($query) =>
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%")
            )
            ->orderByRaw("FIELD(type, 'gouvernorat', 'ville', 'zone')")
            ->limit(10)
            ->get(['id', 'name', 'slug', 'type']);

        return response()->json($regions);
    }

    /**
     * AJAX — disponibilité d'un hostel
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
     * Fiche détail hostel (public) — GET /explore/{hostel}
     */
    public function show(int $id, Request $request)
    {
        $hostel = Hostel::where('is_active', true)
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

    /* ─────────────────────────────────────────────────────────────
     *  Helpers privés
     * ─────────────────────────────────────────────────────────── */

    /**
     * Liste des 6 gouvernorats avec le plus de hostels.
     */
    private function getPopularRegions()
    {
        return Region::gouvernorats()
            ->withCount('hostels')
            ->having('hostels_count', '>', 0)
            ->orderByDesc('hostels_count')
            ->limit(6)
            ->get();
    }

    /**
     * ✨ Données prêtes-à-afficher pour la carte Leaflet de la landing.
     *
     * Retourne pour chaque hostel actif géolocalisé :
     *   { id, name, city, region, lat, lng, image, category, color, url }
     *
     * Catégorisation (basée sur le préfixe du nom) :
     *   - "Complexe de Jeunes..."  → category 'cj' / color 'terra'  (15 hostels)
     *   - "Maison de Jeunes..."    → category 'mj' / color 'teal'   (15 hostels)
     *
     * Le `image` est résolu côté serveur (asset public ou storage symlink)
     * pour que le JS frontend n'ait qu'à utiliser l'URL telle quelle.
     */
    private function getMapHostels()
    {
        return Hostel::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('region:id,name')
            ->get(['id', 'name', 'city', 'latitude', 'longitude', 'cover_image', 'region_id'])
            ->map(function (Hostel $h) {
                // Classification CJ vs MJ
                $isComplexe = str_starts_with(strtolower($h->name), 'complexe');

                // Résolution URL image (même logique que _hostel_card.blade.php)
                $coverUrl = null;
                if ($h->cover_image) {
                    $coverUrl = str_starts_with($h->cover_image, 'images/')
                        ? asset($h->cover_image)
                        : asset('storage/' . $h->cover_image);
                }

                return [
                    'id'       => $h->id,
                    'name'     => $h->name,
                    'city'     => $h->city,
                    'region'   => $h->region?->name,
                    'lat'      => (float) $h->latitude,
                    'lng'      => (float) $h->longitude,
                    'image'    => $coverUrl,
                    'category' => $isComplexe ? 'cj' : 'mj',
                    'color'    => $isComplexe ? 'terra' : 'teal',
                    'url'      => route('search.show', $h->id),
                ];
            })
            ->values();
    }
}
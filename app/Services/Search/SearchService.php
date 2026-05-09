<?php

namespace App\Services\Search;

use App\Models\Hostel;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function __construct(
        private AvailabilityService $availability
    ) {}

    public function search(SearchParams $params): SearchResult
    {
        $query = $this->buildBaseQuery($params);

        if ($params->hasDates()) {
            $this->applyAvailabilityFilter($query, $params);
        }

        $this->applySort($query, $params);

        $query->with([
            'region:id,name,slug',
            'prices' => fn($q) => $q->select('hostel_id', DB::raw('MIN(price_ttc) as min_price'))
                                    ->groupBy('hostel_id'),
        ]);

        $hostels = $query->paginate($params->perPage);

        return new SearchResult(
            $hostels,
            $this->buildFilters($params),
        );
    }

    private function buildBaseQuery(SearchParams $params): Builder
    {
        $query = Hostel::query()
            ->select('hostels.*')
            ->where('hostels.is_active', true);

        // 📍 Filtre région — CTE récursif, profondeur illimitée
        if ($params->regionSlug) {
            $region = Cache::remember(
                "region:slug:{$params->regionSlug}",
                3600,
                fn() => Region::where('slug', $params->regionSlug)->first()
            );

            if ($region) {
                $allRegionIds = Cache::remember(
                    "region:all-ids:{$region->id}",
                    3600,
                    fn() => $region->allDescendantIds()
                );

                $query->whereIn('hostels.region_id', $allRegionIds);
            }
        }

        // 🏕 Filtre type (hostel / camping / mixed)
        if ($params->type) {
            $query->where('hostels.type', $params->type);
        }

        // 🔍 Sous-types cumulables (chambres privées / dortoirs / tentes)
        if (!empty($params->subtypes)) {
            $this->applySubtypeFilter($query, $params);
        }

        // 💰 Filtre prix
        if ($params->minPrice !== null || $params->maxPrice !== null) {
            $query->whereExists(function ($sub) use ($params) {
                $sub->select(DB::raw(1))
                    ->from('prices')
                    ->whereColumn('prices.hostel_id', 'hostels.id');

                if ($params->minPrice !== null) {
                    $sub->where('prices.price_ttc', '>=', $params->minPrice);
                }
                if ($params->maxPrice !== null) {
                    $sub->where('prices.price_ttc', '<=', $params->maxPrice);
                }
            });
        }

        // ⭐ Filtre note
        if ($params->minRating !== null) {
            $query->where('hostels.rating', '>=', $params->minRating);
        }

        return $query;
    }

    /**
     * Filtre cumulable (logique OR) :
     *   - chambres privées      : hostel ayant au moins 1 chambre 'private' active
     *   - chambres dortoir      : hostel ayant au moins 1 chambre 'dormitory' active (avec capacité min optionnelle)
     *   - tentes                : hostel ayant au moins 1 tent_space actif (avec capacité min optionnelle)
     *
     * Cocher plusieurs cases élargit les résultats (OR).
     */
    private function applySubtypeFilter(Builder $query, SearchParams $params): void
    {
        $query->where(function (Builder $q) use ($params) {

            // 🛏 Chambres privées
            if (in_array('private', $params->subtypes, true)) {
                $q->orWhereExists(fn($sub) =>
                    $sub->select(DB::raw(1))
                        ->from('rooms')
                        ->whereColumn('rooms.hostel_id', 'hostels.id')
                        ->where('rooms.type', 'private')
                        ->where('rooms.is_enabled', true)
                );
            }

            // 🛌 Chambres dortoir (+ capacité min optionnelle sur rooms.max_capacity)
            if (in_array('dormitory', $params->subtypes, true)) {
                $q->orWhereExists(function ($sub) use ($params) {
                    $sub->select(DB::raw(1))
                        ->from('rooms')
                        ->whereColumn('rooms.hostel_id', 'hostels.id')
                        ->where('rooms.type', 'dormitory')
                        ->where('rooms.is_enabled', true);

                    if ($params->dormMinCapacity) {
                        $sub->where('rooms.max_capacity', '>=', $params->dormMinCapacity);
                    }
                });
            }

            // 🏕 Tentes (+ capacité min optionnelle sur tent_spaces.max_persons)
            if (in_array('tent', $params->subtypes, true)) {
                $q->orWhereExists(function ($sub) use ($params) {
                    $sub->select(DB::raw(1))
                        ->from('tent_spaces')
                        ->whereColumn('tent_spaces.hostel_id', 'hostels.id')
                        ->where('tent_spaces.is_enabled', true);

                    if ($params->tentMinCapacity) {
                        $sub->where('tent_spaces.max_persons', '>=', $params->tentMinCapacity);
                    }
                });
            }
        });
    }

 private function applyAvailabilityFilter(Builder $query, SearchParams $params): void
{
    $checkIn  = $params->checkIn;
    $checkOut = $params->checkOut;
    $guests   = $params->guests;

    // Capacité totale (chambres + tentes) - occupation >= nb de voyageurs
    $query->whereRaw('
        (
            COALESCE((
                SELECT SUM(rooms.max_capacity)
                FROM rooms
                WHERE rooms.hostel_id = hostels.id
                  AND rooms.is_enabled = 1
            ), 0)
            +
            COALESCE((
                SELECT SUM(tent_spaces.max_persons)
                FROM tent_spaces
                WHERE tent_spaces.hostel_id = hostels.id
                  AND tent_spaces.is_enabled = 1
            ), 0)
            -
            COALESCE((
                SELECT COUNT(*)
                FROM reservation_people rp
                JOIN reservations r ON r.id = rp.reservation_id
                WHERE r.hostel_id = hostels.id
                  AND r.status NOT IN (?)
                  AND r.start_date < ?
                  AND r.end_date > ?
            ), 0)
        ) >= ?
    ', ['cancelled', $checkOut, $checkIn, $guests]);
}

    private function applySort(Builder $query, SearchParams $params): void
    {
        switch ($params->sortBy) {
            case 'price_asc':
                $query->leftJoin(
                    DB::raw('(SELECT hostel_id, MIN(price_ttc) as min_price FROM prices GROUP BY hostel_id) AS price_agg'),
                    'price_agg.hostel_id', '=', 'hostels.id'
                )->orderBy('price_agg.min_price', 'asc')
                 ->orderByRaw('price_agg.min_price IS NULL ASC');
                break;

            case 'price_desc':
                $query->leftJoin(
                    DB::raw('(SELECT hostel_id, MIN(price_ttc) as min_price FROM prices GROUP BY hostel_id) AS price_agg'),
                    'price_agg.hostel_id', '=', 'hostels.id'
                )->orderBy('price_agg.min_price', 'desc');
                break;

            case 'rating':
                $query->orderByDesc('hostels.rating')
                      ->orderByDesc('hostels.total_reviews');
                break;

            case 'popularity':
            default:
                $query->orderByDesc('hostels.total_reviews')
                      ->orderByDesc('hostels.rating')
                      ->orderBy('hostels.id');
                break;
        }
    }

    public function featured(int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("hostels:featured:{$limit}", 600, fn() =>
            Hostel::where('is_active', true)
                ->with([
                    'region:id,name,slug',
                    'prices' => fn($q) => $q->select('hostel_id', DB::raw('MIN(price_ttc) as min_price'))
                                            ->groupBy('hostel_id'),
                ])
                ->orderByDesc('rating')
                ->orderByDesc('total_reviews')
                ->limit($limit)
                ->get()
        );
    }

    private function buildFilters(SearchParams $params): array
    {
        return Cache::remember('search:filters:meta', 600, function () {
            $priceStats = DB::table('prices')
                ->selectRaw('MIN(price_ttc) as min_price, MAX(price_ttc) as max_price')
                ->first();

            return [
                'price_range' => [
                    'min' => (float) ($priceStats->min_price ?? 0),
                    'max' => (float) ($priceStats->max_price ?? 500),
                ],
                'types'   => ['hostel', 'camping', 'mixed'],
                'regions' => Region::gouvernorats()
                    ->withCount(['hostels' => fn($q) => $q->where('is_active', true)])
                    ->having('hostels_count', '>', 0)
                    ->orderByDesc('hostels_count')
                    ->get(['id', 'name', 'slug', 'hostels_count']),
            ];
        });
    }
}
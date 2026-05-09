<?php
// app/Http/Controllers/Api/SearchApiController.php
class SearchApiController extends Controller
{
    public function __construct(private SearchService $search) {}

    // GET /api/v1/search?region=tunis&check_in=2026-06-01&check_out=2026-06-03&guests=2&sort=price_asc
    public function search(Request $request): JsonResponse
    {
        $params = SearchParams::fromRequest($request);
        $result = $this->search->search($params);

        return response()->json([
            'data'    => HostelResource::collection($result->hostels),
            'meta'    => $result->hostels->toArray()['meta'] ?? [],
            'filters' => $result->filters,
        ]);
    }

    // GET /api/v1/availability?hostel_id=1&check_in=2026-06-01&check_out=2026-06-03
    public function availability(Request $request): JsonResponse
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $avail = app(AvailabilityService::class)->getHostelAvailability(
            $request->hostel_id,
            $request->check_in,
            $request->check_out
        );

        return response()->json($avail);
    }

    // GET /api/v1/regions?q=tunis
    public function regions(Request $request): JsonResponse
    {
        $regions = Cache::remember('regions:all', 3600, fn() =>
            Region::with('children')->whereNull('parent_id')->get()
        );

        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $regions = Region::where('name', 'like', "%{$q}%")->limit(10)->get();
        }

        return response()->json($regions);
    }
}
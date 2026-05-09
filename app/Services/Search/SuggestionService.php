<?php
// app/Services/Search/SuggestionService.php
namespace App\Services\Search;

use App\Models\Hostel;

class SuggestionService
{
    public function __construct(
        private AvailabilityService $availability
    ) {}

    public function suggestAlternatives(
        int    $hostelId,
        string $checkIn,
        string $checkOut,
        int    $guests
    ): array {
        $hostel   = Hostel::findOrFail($hostelId);
        $radiusKm = 30;

        if (!$hostel->latitude || !$hostel->longitude) {
            return [];
        }

        $allIds = Hostel::where('is_active', true)
            ->where('id', '!=', $hostelId)
            ->pluck('id')
            ->toArray();

        $availableIds = $this->availability->filterAvailableHostels(
            $allIds, $checkIn, $checkOut, $guests
        );

        if (empty($availableIds)) return [];

        return Hostel::selectRaw("
                *, ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [$hostel->latitude, $hostel->longitude, $hostel->latitude])
            ->whereIn('id', $availableIds)
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->limit(3)
            ->get()
            ->toArray();
    }
}
<?php

namespace App\Services\Search;

use Illuminate\Http\Request;

class SearchParams
{
    public function __construct(
        public readonly ?string $regionSlug      = null,
        public readonly ?string $checkIn         = null,
        public readonly ?string $checkOut        = null,
        public readonly int     $guests          = 1,
        public readonly ?string $type            = null,
        /** @var string[] sous-types cochés : 'private', 'dormitory', 'tent' */
        public readonly array   $subtypes        = [],
        public readonly ?int    $dormMinCapacity = null,
        public readonly ?int    $tentMinCapacity = null,
        public readonly ?float  $minPrice        = null,
        public readonly ?float  $maxPrice        = null,
        public readonly ?float  $minRating       = null,
        public readonly string  $sortBy          = 'popularity',
        public readonly int     $perPage         = 12,
    ) {}

    public static function fromRequest(Request $request): self
    {
        // Whitelist : on accepte uniquement ces 3 valeurs (sécurité anti-injection)
        $subtypes = array_values(array_intersect(
            (array) $request->input('subtypes', []),
            ['private', 'dormitory', 'tent']
        ));

        return new self(
            regionSlug:      $request->input('region'),
            checkIn:         $request->input('check_in'),
            checkOut:        $request->input('check_out'),
            guests:          max(1, (int) $request->input('guests', 1)),
            type:            $request->input('type') ?: null,
            subtypes:        $subtypes,
            dormMinCapacity: $request->filled('dorm_min_capacity') ? (int) $request->input('dorm_min_capacity') : null,
            tentMinCapacity: $request->filled('tent_min_capacity') ? (int) $request->input('tent_min_capacity') : null,
            minPrice:        $request->filled('min_price')         ? (float) $request->input('min_price')      : null,
            maxPrice:        $request->filled('max_price')         ? (float) $request->input('max_price')      : null,
            minRating:       $request->filled('min_rating')        ? (float) $request->input('min_rating')     : null,
            sortBy:          $request->input('sort', 'popularity'),
            perPage:         min(24, max(6, (int) $request->input('per_page', 12))),
        );
    }

    public function hasSearch(): bool
    {
        return $this->regionSlug
            || $this->checkIn
            || $this->type
            || $this->minPrice  !== null
            || $this->maxPrice  !== null
            || !empty($this->subtypes)
            || $this->dormMinCapacity !== null
            || $this->tentMinCapacity !== null
            || $this->minRating !== null;
    }

    public function hasDates(): bool
    {
        return !empty($this->checkIn) && !empty($this->checkOut)
            && $this->checkOut > $this->checkIn;
    }
}
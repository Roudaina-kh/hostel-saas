<?php

namespace App\Services\Search;

use Illuminate\Pagination\LengthAwarePaginator;

class SearchResult
{
    public function __construct(
        public readonly LengthAwarePaginator $hostels,
        public readonly array               $filters,
        public readonly array               $suggestions = [],
    ) {}

    public function isEmpty(): bool
    {
        return $this->hostels->isEmpty();
    }

    public function total(): int
    {
        return $this->hostels->total();
    }
}
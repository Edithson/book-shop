<?php

namespace App\Traits;

use App\Services\Search\TrigramSearchService;
use Illuminate\Database\Eloquent\Builder;

trait SearchableByTrigram
{
    /**
     * Scope Eloquent permettant de rechercher par trigrammes et score de pertinence.
     *
     * @param Builder $query
     * @param string|null $search
     * @param array<string, int> $weights
     * @return Builder
     */
    public function scopeSearchByTrigram(Builder $query, ?string $search, array $weights = []): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return TrigramSearchService::apply($query, $search, $weights);
    }
}

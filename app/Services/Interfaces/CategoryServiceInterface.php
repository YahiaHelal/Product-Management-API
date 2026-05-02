<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryServiceInterface {
    public function listAllCategories(): Collection;

    public function paginatedResult(int $perPage): LengthAwarePaginator;

    public function filter(array $filters, int $perPage): LengthAwarePaginator;
}

<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface {
    public function all(): Collection;

    public function paginatedResult(int $perPage): LengthAwarePaginator;

    public function filter(array $filters, int $perPage): LengthAwarePaginator;
}

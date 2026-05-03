<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface {
    public function all(): Collection;

    public function find(int $id): Category;

    public function create(array $data): Category;

    public function update(int $id, array $data): Category;

    public function delete(int $id): bool;

    public function getActive(): Collection;

    public function getIntactive(): Collection;

    public function filter(array $filters, int $perPage): LengthAwarePaginator;

    public function loadCategoryTree(int $catId): Category;
}

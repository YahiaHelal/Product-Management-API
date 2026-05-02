<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface {

    public function __construct(private CategoryRepositoryInterface $repo) {}

    public function listAllCategories(): Collection
    {
        return $this->repo->all();
    }

    public function paginatedResult(int $perPage): LengthAwarePaginator
    {
        return $this->repo->paginatedResult($perPage);
    }

    public function filter(array $filters, int $perPage): LengthAwarePaginator {
        return $this->repo->filter($filters, $perPage);
    }
}

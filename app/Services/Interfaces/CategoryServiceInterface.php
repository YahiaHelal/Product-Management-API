<?php

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryServiceInterface {
    public function createCategory(array $data): Category;

    public function updateCategory(int $id, array $data): Category;

    public function deleteCategory(int $id): bool;

    public function listAllCategories(): Collection;

    public function listActiveCategories(): Collection;

    public function listInActiveCategories(): Collection;

    public function getCategoryById(int $id): Category;

    public function filterCategories(array $filters, int $perPage = 10): LengthAwarePaginator;
}

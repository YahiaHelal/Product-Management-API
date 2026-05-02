<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Product; // throws an exception if not found

    public function create(array $data): Product;

    public function update(int $id, array $data): Product; // throws an exception if not found

    public function delete(int $id): bool;

    public function getActive(): Collection;

    public function getInActive(): Collection;

    public function getByBrand(string $brand): Collection;

    public function filter(array $filters, int $perPage);
}


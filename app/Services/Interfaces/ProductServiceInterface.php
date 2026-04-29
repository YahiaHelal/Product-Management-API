<?php

namespace App\Services\Interfaces;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function createProduct(array $data): Product;

    public function updateProduct(int $id, array $data): Product;

    public function deleteProduct(int $id): bool;

    public function listActiveProducts(): Collection;

    public function listInActiveProducts(): Collection;

    public function listByBrand(string $brand): Collection;
}

<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Override;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $repo) {
    }


    public function createProduct(array $data): Product
    {
        $this->validatePricing($data);
        return $this->repo->create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        $this->validatePricing($data);
        return $this->repo->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repo->delete($id);
    }
    public function listAllProducts(): Collection {
        return $this->repo->all();
    }

    public function getProductById(int $id): Product
    {
        return $this->repo->find($id);
    }

    public function listActiveProducts(): Collection
    {
        return $this->repo->getActive();
    }

    public function listInActiveProducts(): Collection
    {
        return $this->repo->getInActive();
    }

    public function listByBrand(string $brand): Collection
    {
        return $this->repo->getByBrand($brand);
    }

    private function validatePricing(array $data): void {
        if(isset($data['sale_price'], $data['price']) && $data['sale_price'] > $data['price'])
        {
            throw new InvalidArgumentException('Sale price cannot be greater than price');
        }
    }
}

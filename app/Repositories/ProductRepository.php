<?php

namespace App\Repositories;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function find(int $id): Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product->fresh(); // refresh from db
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id) > 0; // number of deleted rows
    }

    public function getActive(): Collection {
        return Product::active()->get();
    }
    public function getInActive(): Collection {
        return Product::inactive()->get();
    }
    public function getByBrand(string $brand): Collection {
        return Product::byBrand($brand)->get();
    }

    public function filter(array $filters, int $perPage = 10): LengthAwarePaginator {
        $query = Product::query();

        if(!empty($filters['active_only'])) {
            if($filters['active_only'] === 'true') {
                $query->active();
            }else {
                $query->inactive();
            }
        }

        if(!empty($filters['brand'])) {
            $query->byBrand($filters['brand']);
        }

        if(!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        $query->priceRange(
            $filters['min_price'] ?? null,
            $filters['max_price'] ?? null,
        );

        if(!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->paginate($perPage);

    }
}



<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Override;

class CategoryRepository implements CategoryRepositoryInterface {


    public function all(): Collection {
        return Category::all();
    }

    public function paginatedResult(int $perPage): LengthAwarePaginator {
        return Category::query()->paginate($perPage);
    }

    public function filter(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Category::query();

        if(!empty($filters['active_only']) && $filters['active_only'] === 'true') {
            $query->active();
        }
        if(!empty($filters['inactive_only']) && $filters['inactive_only'] === 'true') {
            $query->inactive();
        }

        if(!empty($filters['search'])) {
            $query->search($filters['search']);
        }
        // tree shows all sub-categories
        if(!empty($filters['tree']) && $filters['tree'] === 'true') {
            $query->withChildren();
        }

        return $query->paginate($perPage);
    }
}

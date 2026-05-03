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

    public function find(int $id): Category {
        return Category::findOrFail($id);
    }


    public function create(array $data): Category {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category {
        $cat = Category::findOrFail($id);
        $cat->update($data);

        return $cat->fresh();
    }

    public function delete(int $id): bool {
        return Category::destroy($id) > 0;
    }

    public function getActive(): Collection {
        return Category::active()->get();
    }

    public function getIntactive(): Collection {
        return Category::inactive()->get();
    }

    public function filter(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Category::query();

        if(!empty($filters['active_only'])) {
            if($filters['active_only'] === 'true') {
                $query->active();
            }else {
                $query->inactive();
            }
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

    public function loadCategoryTree(int $catId): Category
    {
        $query = Category::query();
        $tree = $query->withChildren()->find($catId);
        if($tree) {
            return $tree;
        }
        abort(404);
        // return $query->withChildren()->find($catId);
    }

}

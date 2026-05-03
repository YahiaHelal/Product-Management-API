<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    private array $visitedCategories = [];
    public function __construct(private CategoryServiceInterface $categoryService) {}

    public function index(Request $request): JsonResponse {
        $filters = $request->only([
            'active_only',
            'tree',
            'search',
        ]);
        $perPage = (int) $request->query('per_page', 10);

        $cats = $this->categoryService->filterCategories($filters, $perPage);
        $treeView = !empty($filters['tree']) && ($filters['tree'] === 'true');

        return response()->json([
            'locale' => app()->getLocale(),
            'meta' => [
                'current_page' => $cats->currentPage(),
                'last_page' => $cats->lastPage(),
                'per_page' => $cats->perPage(),
                'total' => $cats->total()
            ],
            'data' => ($treeView)
            ? $this->transformCollectionDfs(collect($cats->items()))
            : $this->transformCollection(collect($cats->items())),
        ], 200);
    }

    public function store(StoreCategoryRequest $request): JsonResponse {
        $cat = $this->categoryService->createCategory($request->validated());

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformCategory($cat),
        ], 201);
    }




    private function transformCollection(Collection $cats): array {
        return $cats->map(fn (Category $cat) => $this->transformCategory($cat))->all();
    }

    private function transformCategory(Category $cat): array {
        $translation = $cat->translate(app()->getLocale());

        return [
            'id' => $cat->id,
            'parent_id' => $cat->parent_id,
            'status' => $cat->status,
            'image_path' => $cat->image_path,
            'name' => $translation?->name,
            'created_at' => $cat->created_at,
            'updated_at' => $cat->updated_at,
        ];
    }
    private function transformCollectionDfs(Collection $cats): array {
        $categoryTree = [];
        foreach($cats as $cat) {
            if(!isset($this->visitedCategories[$cat->id])) {
                $this->visitedCategories[$cat->id] = true;
                $categoryTree[] = $this->transformCategoryDfs($cat);
            }
        }
        return $categoryTree;
    }

    private function transformCategoryDfs(Category $cat): array {
        $translation = $cat->translate(app()->getLocale());

        return [
            'id' => $cat->id,
            'parent_id' => $cat->parent_id,
            'status' => $cat->status,
            'image_path' => $cat->image_path,
            'name' => $translation?->name,
            'created_at' => $cat->created_at,
            'updated_at' => $cat->updated_at,
            'sub_categories' => $this->transformCollectionDfs($cat->children),
        ];
    }
}

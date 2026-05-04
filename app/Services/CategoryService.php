<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\ImageServiceInterface;

class CategoryService implements CategoryServiceInterface {

    public function __construct(private CategoryRepositoryInterface $repo, private ImageServiceInterface $imageService) {}

    public function listAllCategories(): Collection
    {
        return $this->repo->all();
    }

    public function getCategoryById(int $id): Category
    {
        return $this->repo->find($id);
    }


    public function createCategory(array $data): Category
    {
        $translations = $this->extractTranslations($data);

        if(isset($data['image']) && $data['image']) {
            $data['image_path'] = $this->imageService->upload($data['image'], 'categories');
            unset($data['image']);
        }

        $cat = $this->repo->create($data);

        $this->syncTranslations($cat, $translations);

        return $cat->fresh();
    }

    public function updateCategory(int $id, array $data): Category
    {
        $translations = $this->extractTranslations($data);
        $currCat = $this->repo->find($id);

        if(isset($data['image']) && $data['image']) {
            $data['image_path'] = $this->imageService->replace($currCat->image_path, $data['image'], 'categories');
            unset($data['image']);
        }
        $cat = $this->repo->update($id, $data);
        $this->syncTranslations($cat, $translations);

        return $cat->fresh();
    }

    public function deleteCategory(int $id): bool
    {
        $cat = $this->repo->find($id);
        if($cat->image_path) {
            $this->imageService->delete($cat->image_path);
        }
        return $this->repo->delete($id);
    }

    public function listActiveCategories(): Collection
    {
        return $this->repo->getActive();
    }

    public function listInActiveCategories(): Collection
    {
        return $this->repo->getIntactive();
    }


    public function filterCategories(array $filters, int $perPage = 10): LengthAwarePaginator {
        return $this->repo->filter($filters, $perPage);
    }

    public function loadCategoryTree(int $catId): Category
    {
        return $this->repo->loadCategoryTree($catId);
    }

    private function extractTranslations(array &$data): array {
        $nameTranslations = $data['name'] ?? [];
        unset($data['name']);
        return [
            'name' => is_array($nameTranslations) ? $nameTranslations : []
        ];
    }

    private function syncTranslations(Category $cat, array $translations): void {
        if(empty($translations['name'])) {
            return;
        }
        foreach($translations['name'] as $locale => $name) {
            $translation = $cat->translateOrNew($locale);
            $translation->name = $name;
        }
        $cat->save();
    }
}

<?php
namespace App\Services;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $repo){}

    public function listAllProducts(): Collection
    {
        return $this->repo->all();
    }

    public function getProductById(int $id): Product
    {
        return $this->repo->find($id);
    }

    public function createProduct(array $data): Product
    {
        $this->validatePricing($data);
        $translations = $this->extractTranslations($data);

        $product = $this->repo->create($data);
        $this->syncTranslations($product, $translations);

        return $product->fresh();
    }

    public function updateProduct(int $id, array $data): Product
    {
        $this->validatePricing($data);
        $translations = $this->extractTranslations($data);

        $product = $this->repo->update($id, $data);
        $this->syncTranslations($product, $translations);

        return $product->fresh();
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repo->delete($id);
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

    public function filterProducts(array $filters, int $perPage = 10): LengthAwarePaginator {
        return $this->repo->filter($filters, $perPage);
    }

    private function validatePricing(array $data): void
    {
        if (isset($data['sale_price'], $data['price']) && $data['sale_price'] > $data['price']) {
            throw new InvalidArgumentException('Sale price cannot be greater than price');
        }
    }

    private function extractTranslations(array &$data): array
    {
        $titleTranslations = $data['title'] ?? [];
        $descriptionTranslations = $data['description'] ?? [];

        unset($data['title'], $data['description']);

        return [
            'title' => is_array($titleTranslations) ? $titleTranslations : [],
            'description' => is_array($descriptionTranslations) ? $descriptionTranslations : [],
        ];
    }

    private function syncTranslations(Product $product, array $translations): void
    {
        $locales = array_unique(array_merge(
            array_keys($translations['title']),
            array_keys($translations['description'])
        ));

        foreach ($locales as $locale) {
            $translation = $product->translateOrNew($locale);

            if (array_key_exists($locale, $translations['title'])) {
                $translation->title = $translations['title'][$locale];
            }

            if (array_key_exists($locale, $translations['description'])) {
                $translation->description = $translations['description'][$locale];
            }
        }

        if (!empty($locales)) {
            $product->save();
        }
    }
}

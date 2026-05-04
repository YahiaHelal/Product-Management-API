<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ImageServiceInterface;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $repo, private ImageServiceInterface $imageService){}

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

        if(isset($data['main_image']) && $data['main_image']) {
            $data['main_image_path'] = $this->imageService->upload($data['main_image'], 'products/main');
            unset($data['main_image']);
        }

        $galleryImages = $data['gallery_images'] ?? [];
        unset($data['gallery_images']);

        $product = $this->repo->create($data);

        if(!empty($galleryImages)) {
            $this->attachGalleryImages($product, $galleryImages);
        }
        $this->syncTranslations($product, $translations);

        return $product->load('images'); // re-load images only
    }

    public function updateProduct(int $id, array $data): Product
    {
        $this->validatePricing($data);
        $translations = $this->extractTranslations($data);
        $product = $this->repo->find($id);

        if(isset($data['main_image']) && $data['main_image']) {
            $data['main_image_path'] = $this->imageService->replace($product->main_image_path, $data['main_image'], 'products/main');
            unset($data['main_image']);
        }

        if(isset($data['delete_gallery_images']) && !empty($data['delete_gallery_images'])) {
            $this->deleteGalleryImages($product, $data['delete_gallery_images']);
            unset($data['delete_gallery_images']);
        }

        $galleryImages = $data['gallery_images'] ?? [];
        unset($data['gallery_images']);

        $product = $this->repo->update($id, $data);

        if(!empty($galleryImages)) {
            $this->attachGalleryImages($product, $galleryImages);
        }


        $this->syncTranslations($product, $translations);

        return $product->load('images');
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
        if (isset($data['sale_price'], $data['price']) && $data['sale_price'] >= $data['price']) {
            throw new InvalidArgumentException('Sale price cannot be greater than or equal to price');
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

    private function attachGalleryImages(Product $product, array $images): void {
        $imagePaths = $this->imageService->uploadMultiple($images, 'products/gallery');
        foreach($imagePaths as $path) {
            $product->images()->create(['image_path' => $path]);
        }
    }

    private function deleteGalleryImages(Product $product, array $imageIds): void {
        $imagesToDelete = ProductImage::whereIn('id', $imageIds)->where('product_id', $product->id)->get();

        foreach($imagesToDelete as $image) {
            $this->imageService->delete($image->image_path);
            $image->delete();
        }
    }
}

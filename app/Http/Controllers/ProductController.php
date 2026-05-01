<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService) {}

    public function index(Request $request): JsonResponse {
        if($request->boolean('active_only')) {
            $products = $this->productService->listActiveProducts();
        }else if($request->boolean('inactive_only')) {
            $products = $this->productService->listInActiveProducts();
        }else if($request->filled('brand')) {
            $products = $this->productService->listByBrand((string) $request->query('brand'));
        }else {
            $products = $this->productService->listAllProducts();
        }

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformCollection($products),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformProduct($product),
        ], 201);
    }

    public function show(int $product): JsonResponse
    {
        $item = $this->productService->getProductById($product);

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformProduct($item),
        ]);
    }

    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        $item = $this->productService->updateProduct($product, $request->validated());

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformProduct($item),
        ]);
    }

    public function destroy(int $product): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($product);

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => $deleted ? 'Product Deleted Successfully' : 'Product Not Found',
        ], $deleted ? 200 : 404);
    }

    private function transformCollection(Collection $products): array
    {
        return $products->map(fn (Product $product) => $this->transformProduct($product))->all();
    }

    private function transformProduct(Product $product): array
    {
        $translation = $product->translate(app()->getLocale());

        return [
            'id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'stock' => $product->stock,
            'brand' => $product->brand,
            'main_image_path' => $product->main_image_path,
            'status' => $product->status,
            'category_id' => $product->category_id,
            'title' => $translation?->title,
            'description' => $translation?->description,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }
}

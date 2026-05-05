<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse {
        $user = Auth::guard('api')->user();

        $favs = $user->favorites()->with(['images', 'category']);

        return response()->json([
            'locale' => app()->getLocale(),
            'data' => $this->transformCollection(collect($favs->items())),
        ]);
    }

    public function store(int $prodId): JsonResponse {
        $user = Auth::guard('api')->user();

        $prod = Product::findOrFail($prodId);

        if($user->favorites()->where('product_id', $prodId)->eixsts()) {
            return response()->json([
                'locale' => app()->getLocale(),
                'message' => 'Product already in your favorites',
            ], 409);
        }

        $user->favorites()->attach($prodId);
        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Proudct added to your favorites successfully',
        ], 201);
    }

    public function destroy(int $prodId): JsonResponse {
        $user = Auth::guard('api')->user();

        $removed = $user->favorites()->detach($prodId);

        if(!$removed) {
            return response()->json([
                'locale' => app()->getLocale(),
                'message' => 'product is not in your favorites already',
            ], 404);
        }

        return response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Product removed from your favorites',
        ]);
    }

    private function transformCollection(Collection $products): array {
        return $products->map(fn (Product $product) => $this->transformProduct($product))->all();
    }

    private function transformProduct(Product $product): array {
        $translation = $product->translate(app()->getLocale());
            return [
                'id' => $product->id,
                'sku' => $product->sku,
                'title' => $translation?->title,
                'description' => $translation?->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'brand' => $product->brand,
                'main_image_url' => $product->main_image_url,
                'category_id' => $product->category_id,
            ];
    }
}

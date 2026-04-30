<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService) {}

    public function index(Request $request): JsonResponse {
        if($request->boolean('active-only')) {
            $products = $this->productService->listActiveProducts();
        }else if($request->boolean('inactive-only')) {
            $products = $this->productService->listInActiveProducts();
        }else if($request->filled('brand')) {
            $products = $this->productService->listByBrand((string) $request->query('brand'));
        }else {
            $products = $this->productService->listAllProducts();
        }

        return response()->json(['data' => $products]);
    }

    public function show(int $id) {
        return $this->productService->getProductById($id);
    }
}

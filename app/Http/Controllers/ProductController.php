<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ProductServiceInterface;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService) {}

    public function index() {
        return $this->productService->listAllProducts();
    }
    public function show(int $id) {
        return $this->productService->getProductById($id);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laptopsCategory = Category::where('image_path', 'docs/labtops.jpg')->firstOrFail();
        $fashionCategory = Category::where('image_path', 'docs/fashion.jpg')->firstOrFail();

        $laptopProduct = new Product();
        $laptopProduct->fill([
            'sku' => 'LAP-1001',
            'price' => 1299.99,
            'sale_price' => 1199.99,
            'stock' => 25,
            'brand' => 'ApexTech',
            'main_image_path' => 'docs/products/laptop-pro-15.jpg',
            'status' => true,
            'category_id' => $laptopsCategory->id,
        ]);

        $laptopProduct->translateOrNew('en')->title = 'Laptop Pro 15';
        $laptopProduct->translateOrNew('en')->description = 'High-performance laptop for productivity and creative work.';
        $laptopProduct->translateOrNew('ar')->title = 'لابتوب برو 15';
        $laptopProduct->translateOrNew('ar')->description = 'لابتوب عالي الأداء للإنتاجية والأعمال الإبداعية.';
        $laptopProduct->save();

        $fashionProduct = new Product();
        $fashionProduct->fill([
            'sku' => 'FSH-2001',
            'price' => 79.99,
            'sale_price' => null,
            'stock' => 120,
            'brand' => 'UrbanLine',
            'main_image_path' => 'docs/products/classic-jacket.jpg',
            'status' => true,
            'category_id' => $fashionCategory->id,
        ]);

        $fashionProduct->translateOrNew('en')->title = 'Classic Jacket';
        $fashionProduct->translateOrNew('en')->description = 'Comfortable everyday jacket with a timeless design.';
        $fashionProduct->translateOrNew('ar')->title = 'جاكيت كلاسيكي';
        $fashionProduct->translateOrNew('ar')->description = 'جاكيت مريح للاستخدام اليومي بتصميم كلاسيكي.';
        $fashionProduct->save();
    }
}

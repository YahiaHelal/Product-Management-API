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
        $laptopsCategory = Category::whereTranslation('name', 'Labtops', 'en')->first();
        $fashionCategory = Category::whereTranslation('name', 'Fashion', 'en')->first();

        if ($laptopsCategory) {
            $macbook = new Product();
            $macbook->fill([
                'sku' => 'LAP-APPLE-MBP14',
                'price' => 1999.99,
                'sale_price' => 1799.99,
                'stock' => 25,
                'brand' => 'Apple',
                'main_image_path' => 'docs/labtops.jpg',
                'status' => true,
                'category_id' => $laptopsCategory->id,
                'category_translation_id' => $laptopsCategory->translate('en')->id,
            ]);
            $macbook->translateOrNew('en')->title = 'MacBook Pro 14"';
            $macbook->translateOrNew('en')->description = 'Powerful 14-inch laptop for developers and creators.';
            $macbook->translateOrNew('ar')->title = 'ماك بوك برو 14"';
            $macbook->translateOrNew('ar')->description = 'حاسوب محمول قوي مقاس 14 بوصة للمطورين وصناع المحتوى.';
            $macbook->save();
        }

        if ($fashionCategory) {
            $jacket = new Product();
            $jacket->fill([
                'sku' => 'FSH-JACKET-001',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock' => 80,
                'brand' => 'UrbanWear',
                'main_image_path' => 'docs/fashion.jpg',
                'status' => true,
                'category_id' => $fashionCategory->id,
                'category_translation_id' => $fashionCategory->translate('en')->id,
            ]);
            $jacket->translateOrNew('en')->title = 'Casual Denim Jacket';
            $jacket->translateOrNew('en')->description = 'Classic fit denim jacket suitable for daily wear.';
            $jacket->translateOrNew('ar')->title = 'جاكيت جينز كاجوال';
            $jacket->translateOrNew('ar')->description = 'جاكيت جينز بقصة كلاسيكية مناسب للاستخدام اليومي.';
            $jacket->save();
        }
    }
}

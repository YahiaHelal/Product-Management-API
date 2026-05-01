<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory(10)->create();

        foreach ($categories as $category) {
            $category->translateOrNew('en')->name = fake()->word();
            $category->translateOrNew('ar')->name = fake()->word();
            $category->save();
        }

        $products = Product::factory(100)->make();

        foreach ($products as $product) {
            $product->category_id = $categories->random()->id;
            $product->save();

            $product->translateOrNew('en')->title = fake()->sentence(3);
            $product->translateOrNew('en')->description = fake()->sentence(10);

            $product->translateOrNew('ar')->title = fake()->sentence(3);
            $product->translateOrNew('ar')->description = fake()->sentence(10);

            $product->save();
        }
    }
}

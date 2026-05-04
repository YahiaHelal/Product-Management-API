<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 50, 5000);
        $hasSale = $this->faker->boolean(30); // 30% chance

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####-????')),
            'price' => $price,
            'sale_price' => $hasSale ? $price * $this->faker->randomFloat(2, 0.7, 0.9) : null,
            'stock' => $this->faker->numberBetween(0, 500),
            'brand' => $this->faker->randomElement([
                'Apple', 'Samsung', 'Sony', 'LG', 'Dell', 'HP',
                'Lenovo', 'Asus', 'Microsoft', 'Nintendo',
                'Logitech', 'Razer', 'Corsair', 'IKEA', 'Philips'
            ]),
            'status' => $this->faker->boolean(90), // 90% active
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($product) {

            $product->translateOrNew('en')->title = $this->faker->sentence(3);
            $product->translateOrNew('en')->description = $this->faker->paragraph(3);

            $product->translateOrNew('ar')->title = 'منتج ' . $this->faker->word();
            $product->translateOrNew('ar')->description = 'وصف المنتج باللغة العربية';

            $product->save();
        });
    }

    public function laptop(): static
    {
        return $this->state(function (array $attributes) {
            $laptopCategory = Category::whereHas('translations', function ($query) {
                $query->where('name', 'Laptops');
            })->first();

            return [
                'category_id' => $laptopCategory?->id ?? $attributes['category_id'],
                'brand' => $this->faker->randomElement(['Dell', 'HP', 'Lenovo', 'Asus', 'Apple']),
                'price' => $this->faker->randomFloat(2, 800, 3000),
            ];
        })->afterCreating(function ($product) {
            $product->attributes()->createMany([
                ['name' => 'RAM', 'value' => $this->faker->randomElement(['8GB', '16GB', '32GB', '64GB'])],
                ['name' => 'Storage', 'value' => $this->faker->randomElement(['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD'])],
                ['name' => 'Screen Size', 'value' => $this->faker->randomElement(['13"', '14"', '15.6"', '17"'])],
                ['name' => 'Processor', 'value' => $this->faker->randomElement(['Intel i5', 'Intel i7', 'Intel i9', 'AMD Ryzen 5', 'AMD Ryzen 7'])],
                ['name' => 'Graphics', 'value' => $this->faker->randomElement(['Integrated', 'NVIDIA GTX 1650', 'NVIDIA RTX 3050', 'NVIDIA RTX 3060'])],
            ]);
        });
    }

    public function smartphone(): static
    {
        return $this->state(function (array $attributes) {
            $smartphoneCategory = Category::whereHas('translations', function ($query) {
                $query->where('name', 'Smartphones');
            })->first();

            return [
                'category_id' => $smartphoneCategory?->id ?? $attributes['category_id'],
                'brand' => $this->faker->randomElement(['Apple', 'Samsung', 'Google', 'OnePlus', 'Xiaomi']),
                'price' => $this->faker->randomFloat(2, 300, 1500),
            ];
        })->afterCreating(function ($product) {
            $product->attributes()->createMany([
                ['name' => 'RAM', 'value' => $this->faker->randomElement(['4GB', '6GB', '8GB', '12GB', '16GB'])],
                ['name' => 'Storage', 'value' => $this->faker->randomElement(['64GB', '128GB', '256GB', '512GB', '1TB'])],
                ['name' => 'Screen Size', 'value' => $this->faker->randomElement(['5.5"', '6.1"', '6.5"', '6.7"'])],
                ['name' => 'Camera', 'value' => $this->faker->randomElement(['12MP', '48MP', '64MP', '108MP'])],
                ['name' => 'Battery', 'value' => $this->faker->randomElement(['3000mAh', '4000mAh', '5000mAh', '6000mAh'])],
                ['name' => 'Color', 'value' => $this->faker->randomElement(['Black', 'White', 'Blue', 'Red', 'Gold'])],
            ]);
        });
    }

    public function gamingConsole(): static
    {
        return $this->state(function (array $attributes) {
            $consoleCategory = Category::whereHas('translations', function ($query) {
                $query->where('name', 'Gaming Consoles');
            })->first();

            return [
                'category_id' => $consoleCategory?->id ?? $attributes['category_id'],
                'brand' => $this->faker->randomElement(['Sony', 'Microsoft', 'Nintendo']),
                'price' => $this->faker->randomFloat(2, 300, 600),
            ];
        })->afterCreating(function ($product) {
            $product->attributes()->createMany([
                ['name' => 'Storage', 'value' => $this->faker->randomElement(['512GB SSD', '1TB SSD', '2TB SSD'])],
                ['name' => 'Resolution', 'value' => $this->faker->randomElement(['1080p', '4K', '8K'])],
                ['name' => 'Color', 'value' => $this->faker->randomElement(['White', 'Black'])],
            ]);
        });
    }

}

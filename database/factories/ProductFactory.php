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
        $price = $this->faker->randomFloat(2, 10, 2000);
        $salePrice = $this->faker->boolean(30)
            ? $this->faker->randomFloat(2, 5, $price)
            : null;

        return [
            'sku' => strtoupper($this->faker->unique()->bothify('PRD-####')),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock' => $this->faker->numberBetween(0, 200),
            'brand' => $this->faker->randomElement([
                'Apple', 'Samsung', 'Nike', 'Adidas', 'Sony', 'Dell'
            ]),
            'main_image_path' => 'docs/products/' . $this->faker->word() . '.jpg',
            'status' => $this->faker->boolean(85),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
        ];
    }
    
}

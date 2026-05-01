<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'status' => $this->faker->boolean(90),
            'image_path' => 'docs/' . $this->faker->word() . '.jpg',
        ];
    }
}

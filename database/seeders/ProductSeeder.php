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
        Product::query()->delete();

        Product::factory()->laptop()->count(10)->create();

        Product::factory()->smartphone()->count(15)->create();

        Product::factory()->gamingConsole()->count(5)->create();

        Product::factory()->count(20)->create();

        $this->command->info('Products seeded successfully!');
    }
}

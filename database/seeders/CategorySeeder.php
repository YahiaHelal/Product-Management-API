<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $electronics = new Category();
        $electronics->fill([
            'parent_id' => null,
            'status' => true,
            'image_path' => 'docs/electronics.jpg',
        ]);
        $electronics->translateOrNew('en')->name = 'Electronics';
        $electronics->translateOrNew('ar')->name = 'الكترونيات';

        $electronics->save();
        $labtops = new Category();

        $labtops->fill([
            'parent_id' => $electronics->id,
            'status' => true,
            'image_path' => 'docs/labtops.jpg',
        ]);

        $labtops->translateOrNew('en')->name = 'Labtops';
        $labtops->translateOrNew('ar')->name = 'اجهزة لابتوب';

        $labtops->save();

        $fashion = new Category();
        $fashion->fill([
            'parent_id' => null,
            'status' => true,
            'image_path' => 'docs/fashion.jpg',
            ]);
        $fashion->translateOrNew('en')->name = 'Fashion';
        $fashion->translateOrNew('ar')->name = 'موضة';
        $fashion->save();
    }
}

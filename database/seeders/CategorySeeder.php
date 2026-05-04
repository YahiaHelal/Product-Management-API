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

        Category::query()->delete();

        // root
        $electronics = $this->createCategory(null, [
            'en' => 'Electronics',
            'ar' => 'الإلكترونيات'
        ]);

        // Electronics > Computers
        $computers = $this->createCategory($electronics->id, [
            'en' => 'Computers',
            'ar' => 'الحواسيب'
        ]);

        // Electronics > Computers > Laptops
        $this->createCategory($computers->id, [
            'en' => 'Laptops',
            'ar' => 'اللابتوبات'
        ]);

        // Electronics > Computers > Desktops
        $this->createCategory($computers->id, [
            'en' => 'Desktops',
            'ar' => 'أجهزة المكتب'
        ]);

        // Electronics > Computers > Accessories
        $computerAccessories = $this->createCategory($computers->id, [
            'en' => 'Computer Accessories',
            'ar' => 'إكسسوارات الحاسوب'
        ]);

        // Electronics > Computers > Accessories > Keyboards
        $this->createCategory($computerAccessories->id, [
            'en' => 'Keyboards',
            'ar' => 'لوحات المفاتيح'
        ]);

        // Electronics > Computers > Accessories > Mice
        $this->createCategory($computerAccessories->id, [
            'en' => 'Mice',
            'ar' => 'الفأرات'
        ]);

        // Electronics > Mobile Phones
        $mobilePhones = $this->createCategory($electronics->id, [
            'en' => 'Mobile Phones',
            'ar' => 'الهواتف المحمولة'
        ]);

        // Electronics > Mobile Phones > Smartphones
        $this->createCategory($mobilePhones->id, [
            'en' => 'Smartphones',
            'ar' => 'الهواتف الذكية'
        ]);

        // Electronics > Mobile Phones > Tablets
        $this->createCategory($mobilePhones->id, [
            'en' => 'Tablets',
            'ar' => 'الأجهزة اللوحية'
        ]);

        // Electronics > Mobile Phones > Phone Accessories
        $phoneAccessories = $this->createCategory($mobilePhones->id, [
            'en' => 'Phone Accessories',
            'ar' => 'إكسسوارات الهواتف'
        ]);

        // Electronics > Mobile Phones > Phone Accessories > Cases
        $this->createCategory($phoneAccessories->id, [
            'en' => 'Phone Cases',
            'ar' => 'أغطية الهواتف'
        ]);

        // Electronics > Mobile Phones > Phone Accessories > Chargers
        $this->createCategory($phoneAccessories->id, [
            'en' => 'Chargers',
            'ar' => 'الشواحن'
        ]);

        // Electronics > Gaming
        $gaming = $this->createCategory($electronics->id, [
            'en' => 'Gaming',
            'ar' => 'الألعاب'
        ]);

        // Electronics > Gaming > Consoles
        $this->createCategory($gaming->id, [
            'en' => 'Gaming Consoles',
            'ar' => 'أجهزة الألعاب'
        ]);

        // Electronics > Gaming > Games
        $this->createCategory($gaming->id, [
            'en' => 'Video Games',
            'ar' => 'ألعاب الفيديو'
        ]);

        // Electronics > Gaming > Controllers
        $this->createCategory($gaming->id, [
            'en' => 'Gaming Controllers',
            'ar' => 'أذرع التحكم'
        ]);

        // Home & Kitchen (Root)
        $homeKitchen = $this->createCategory(null, [
            'en' => 'Home & Kitchen',
            'ar' => 'المنزل والمطبخ'
        ]);

        // Home & Kitchen > Furniture
        $furniture = $this->createCategory($homeKitchen->id, [
            'en' => 'Furniture',
            'ar' => 'الأثاث'
        ]);

        // Home & Kitchen > Furniture > Living Room
        $this->createCategory($furniture->id, [
            'en' => 'Living Room Furniture',
            'ar' => 'أثاث غرفة المعيشة'
        ]);

        // Home & Kitchen > Furniture > Bedroom
        $this->createCategory($furniture->id, [
            'en' => 'Bedroom Furniture',
            'ar' => 'أثاث غرفة النوم'
        ]);

        // Home & Kitchen > Appliances
        $appliances = $this->createCategory($homeKitchen->id, [
            'en' => 'Kitchen Appliances',
            'ar' => 'أجهزة المطبخ'
        ]);

        // Home & Kitchen > Appliances > Small Appliances
        $this->createCategory($appliances->id, [
            'en' => 'Small Appliances',
            'ar' => 'الأجهزة الصغيرة'
        ]);

        // Home & Kitchen > Appliances > Large Appliances
        $this->createCategory($appliances->id, [
            'en' => 'Large Appliances',
            'ar' => 'الأجهزة الكبيرة'
        ]);

        // Fashion (Root)
        $fashion = $this->createCategory(null, [
            'en' => 'Fashion',
            'ar' => 'الموضة'
        ]);

        // Fashion > Men
        $menFashion = $this->createCategory($fashion->id, [
            'en' => "Men's Fashion",
            'ar' => 'أزياء الرجال'
        ]);

        // Fashion > Men > Clothing
        $this->createCategory($menFashion->id, [
            'en' => "Men's Clothing",
            'ar' => 'ملابس الرجال'
        ]);

        // Fashion > Men > Shoes
        $this->createCategory($menFashion->id, [
            'en' => "Men's Shoes",
            'ar' => 'أحذية الرجال'
        ]);

        // Fashion > Women
        $womenFashion = $this->createCategory($fashion->id, [
            'en' => "Women's Fashion",
            'ar' => 'أزياء النساء'
        ]);

        // Fashion > Women > Clothing
        $this->createCategory($womenFashion->id, [
            'en' => "Women's Clothing",
            'ar' => 'ملابس النساء'
        ]);

        // Fashion > Women > Shoes
        $this->createCategory($womenFashion->id, [
            'en' => "Women's Shoes",
            'ar' => 'أحذية النساء'
        ]);

        // Sports & Outdoors (Root)
        $sports = $this->createCategory(null, [
            'en' => 'Sports & Outdoors',
            'ar' => 'الرياضة والهواء الطلق'
        ]);

        // Sports & Outdoors > Exercise & Fitness
        $fitness = $this->createCategory($sports->id, [
            'en' => 'Exercise & Fitness',
            'ar' => 'التمارين واللياقة'
        ]);

        // Sports & Outdoors > Exercise & Fitness > Cardio Equipment
        $this->createCategory($fitness->id, [
            'en' => 'Cardio Equipment',
            'ar' => 'معدات الكارديو'
        ]);

        // Sports & Outdoors > Exercise & Fitness > Strength Training
        $this->createCategory($fitness->id, [
            'en' => 'Strength Training',
            'ar' => 'تمارين القوة'
        ]);

        // Sports & Outdoors > Outdoor Recreation
        $outdoor = $this->createCategory($sports->id, [
            'en' => 'Outdoor Recreation',
            'ar' => 'الترفيه الخارجي'
        ]);

        // Sports & Outdoors > Outdoor Recreation > Camping
        $this->createCategory($outdoor->id, [
            'en' => 'Camping & Hiking',
            'ar' => 'التخييم والمشي'
        ]);

        $this->command->info('Categories seeded successfully!');
    }
    private function createCategory(?int $parentId, array $names, bool $status = true): Category
    {
        $category = Category::create([
            'parent_id' => $parentId,
            'status' => $status,
        ]);

        foreach ($names as $locale => $name) {
            $category->translateOrNew($locale)->name = $name;
        }

        $category->save();

        return $category;
    }
}

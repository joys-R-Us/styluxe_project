<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tops', 'slug' => 'tops', 'icon' => '👚'],
            ['name' => 'Bottoms', 'slug' => 'bottoms', 'icon' => '👖'],
            ['name' => 'Dresses', 'slug' => 'dresses', 'icon' => '👗'],
            ['name' => 'Outerwear', 'slug' => 'outerwear', 'icon' => '🧥'],
            ['name' => 'Jumpsuits', 'slug' => 'jumpsuits', 'icon' => '🩱'],
            ['name' => 'Shoes', 'slug' => 'shoes', 'icon' => '👟'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => '👜'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'icon' => $category['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
?>
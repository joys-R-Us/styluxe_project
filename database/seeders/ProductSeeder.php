<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('No admin user found. Run UserSeeder first.');
            return;
        }

        $products = [
            [
                'item_name' => 'Vintage Denim Jacket',
                'category' => 'Outerwear',
                'size' => 'M',
                'color' => 'Blue',
                'condition' => 'Vintage',
                'description' => 'Classic vintage denim jacket in excellent condition',
                'quantity' => 5,
                'price' => 1500.00,
                'status' => 'Available',
            ],
            [
                'item_name' => 'Floral Summer Dress',
                'category' => 'Dresses',
                'size' => 'S',
                'color' => 'Pink',
                'condition' => 'New',
                'description' => 'Beautiful floral print summer dress',
                'quantity' => 12,
                'price' => 899.00,
                'status' => 'Available',
            ],
            [
                'item_name' => 'High-Waist Jeans',
                'category' => 'Bottoms',
                'size' => 'L',
                'color' => 'Black',
                'condition' => 'Pre-Loved',
                'description' => 'Trendy high-waist jeans, barely used',
                'quantity' => 8,
                'price' => 650.00,
                'status' => 'Available',
            ],
            [
                'item_name' => 'Branded White Sneakers',
                'category' => 'Shoes',
                'size' => '8',
                'color' => 'White',
                'condition' => 'Branded',
                'description' => 'Premium white sneakers from known brand',
                'quantity' => 3,
                'price' => 2500.00,
                'status' => 'Out-Of-Stock',
            ],
            [
                'item_name' => 'Oversized Hoodie',
                'category' => 'Tops',
                'size' => 'XL',
                'color' => 'Gray',
                'condition' => 'New',
                'description' => 'Comfortable oversized hoodie',
                'quantity' => 15,
                'price' => 799.00,
                'status' => 'Available',
            ],
        ];

        foreach ($products as $product) {
            // Resolve category name to category_id, create if missing
            $category = Category::where('name', $product['category'])->first();
            if (! $category) {
                $category = Category::create([
                    'name' => $product['category'],
                    'slug' => Str::slug($product['category']),
                ]);
            }

            $data = array_merge($product, [
                'category_id' => $category->id,
                'added_by' => $admin->id,
                'reorder_level' => 5,
                'low_stock_threshold' => 10,
            ]);

            // Remove legacy 'category' key if present
            unset($data['category']);

            Products::create($data);
        }
    }
}
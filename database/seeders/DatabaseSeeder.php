<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 3 Actors as per your documents
        $admin = User::create([
            'username' => 'foodhouse_admin',
            'email' => 'admin@foodhouse.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'full_name' => 'Foodhouse Administrator',
            'phone_number' => '09171234567',
            'status' => 'active'
        ]);

        $cashier = User::create([
            'username' => 'cashier_juan',
            'email' => 'cashier@foodhouse.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
            'full_name' => 'Juan Dela Cruz',
            'phone_number' => '09171234568',
            'status' => 'active'
        ]);

        $customer = User::create([
            'username' => 'customer_maria',
            'email' => 'customer@foodhouse.com',
            'password' => Hash::make('customer123'), // FIXED: Added =>
            'role' => 'customer',
            'full_name' => 'Maria Santos',
            'phone_number' => '09171234569',
            'status' => 'active'
        ]);

        // Create categories based on your ERD
        $categories = [
            ['name' => 'Appetizers', 'description' => 'Starters and finger foods'],
            ['name' => 'Main Course', 'description' => 'Hearty meals and entrees'],
            ['name' => 'Desserts', 'description' => 'Sweet treats and pastries'],
            ['name' => 'Beverages', 'description' => 'Drinks and refreshments'],
            ['name' => 'Rice Meals', 'description' => 'Rice-based dishes'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create menu items for Foodhouse Restaurant
        $menuItems = [
            // Appetizers
            [
                'item_code' => 'FHAPP001',
                'item_name' => 'Crispy Calamares',
                'description' => 'Deep fried squid rings with garlic mayo dip',
                'category_id' => 1,
                'price' => 150.00,
                'cost' => 75.00,
                'stock_quantity' => 50,
                'reorder_level' => 10,
                'unit_of_measurement' => 'serving'
            ],
            [
                'item_code' => 'FHAPP002',
                'item_name' => 'Nachos Supreme',
                'description' => 'Tortilla chips with cheese, ground beef, and salsa',
                'category_id' => 1,
                'price' => 180.00,
                'cost' => 90.00,
                'stock_quantity' => 40,
                'reorder_level' => 8,
                'unit_of_measurement' => 'serving'
            ],

            // Main Course
            [
                'item_code' => 'FHMC001',
                'item_name' => 'Grilled Pork Chop',
                'description' => 'Juicy pork chop with garlic rice and vegetables',
                'category_id' => 2,
                'price' => 220.00,
                'cost' => 110.00,
                'stock_quantity' => 30,
                'reorder_level' => 6,
                'unit_of_measurement' => 'serving'
            ],
            [
                'item_code' => 'FHMC002',
                'item_name' => 'Beef Steak',
                'description' => 'Tender beef strips with onions and gravy',
                'category_id' => 2,
                'price' => 250.00,
                'cost' => 125.00,
                'stock_quantity' => 25,
                'reorder_level' => 5,
                'unit_of_measurement' => 'serving'
            ],

            // Rice Meals
            [
                'item_code' => 'FHRM001',
                'item_name' => 'Chicken Adobo',
                'description' => 'Classic Filipino chicken adobo with rice',
                'category_id' => 5,
                'price' => 160.00,
                'cost' => 80.00,
                'stock_quantity' => 60,
                'reorder_level' => 12,
                'unit_of_measurement' => 'serving'
            ],
            [
                'item_code' => 'FHRM002',
                'item_name' => 'Pork Sinigang',
                'description' => 'Sour pork soup with vegetables and rice',
                'category_id' => 5,
                'price' => 170.00,
                'cost' => 85.00,
                'stock_quantity' => 55,
                'reorder_level' => 11,
                'unit_of_measurement' => 'serving'
            ],

            // Desserts
            [
                'item_code' => 'FHDES001',
                'item_name' => 'Leche Flan',
                'description' => 'Creamy caramel custard',
                'category_id' => 3,
                'price' => 80.00,
                'cost' => 40.00,
                'stock_quantity' => 100,
                'reorder_level' => 20,
                'unit_of_measurement' => 'piece'
            ],
            [
                'item_code' => 'FHDES002',
                'item_name' => 'Halo-Halo Special',
                'description' => 'Mixed sweet beans, fruits, and ice cream',
                'category_id' => 3,
                'price' => 120.00,
                'cost' => 60.00,
                'stock_quantity' => 80,
                'reorder_level' => 16,
                'unit_of_measurement' => 'glass'
            ],

            // Beverages
            [
                'item_code' => 'FHBV001',
                'item_name' => 'Iced Tea',
                'description' => 'Refreshing iced tea',
                'category_id' => 4,
                'price' => 50.00,
                'cost' => 25.00,
                'stock_quantity' => 200,
                'reorder_level' => 40,
                'unit_of_measurement' => 'glass'
            ],
            [
                'item_code' => 'FHBV002',
                'item_name' => 'Fresh Lemonade',
                'description' => 'Freshly squeezed lemonade',
                'category_id' => 4,
                'price' => 60.00,
                'cost' => 30.00,
                'stock_quantity' => 150,
                'reorder_level' => 30,
                'unit_of_measurement' => 'glass'
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }

        echo "✅ Database seeded successfully with 3 actors and sample menu items\n";
    }
}
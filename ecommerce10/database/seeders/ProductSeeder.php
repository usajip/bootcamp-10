<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_categories = ['Electronics', 'Clothing', 'Books', 'Home & Kitchen', 'Sports'];
        
        foreach ($product_categories as $category) {
            //check if exists before inserting
            if (DB::table('product_categories')->where('name', $category . ' Product')->exists()) {
                continue;
            }
            DB::table('product_categories')->insert([
                'name' => $category . ' Product',
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $product_of_electronics = [
            ['name' => 'Smartphone', 'category_id' => 1, 'price' => 299, 'slug' => Str::slug('Smartphone'), 'description'=>'A high-quality smartphone with the latest features.'],
            ['name' => 'Laptop', 'category_id' => 1, 'price' => 799, 'slug' => Str::slug('Laptop'), 'description'=>'A powerful laptop suitable for work and gaming.'],
            ['name' => 'Tablet', 'category_id' => 1, 'price' => 399, 'slug' => Str::slug('Tablet'), 'description'=>'A versatile tablet suitable for work and entertainment.'],
            ['name' => 'Smartwatch', 'category_id' => 1, 'price' => 199, 'slug' => Str::slug('Smartwatch'), 'description'=>'A stylish smartwatch with various health tracking features.'],
            ['name' => 'Wireless Earbuds', 'category_id' => 1, 'price' => 149, 'slug' => Str::slug('Wireless Earbuds'), 'description'=>'High-quality wireless earbuds with excellent sound and battery life.'],
            ['name' => 'Gaming Console', 'category_id' => 1, 'price' => 499, 'slug' => Str::slug('Gaming Console'), 'description'=>'A popular gaming console with a wide range of games.'],
            ['name' => 'Bluetooth Speaker', 'category_id' => 1, 'price' => 99, 'slug' => Str::slug('Bluetooth Speaker'), 'description'=>'A portable Bluetooth speaker with excellent sound quality.'],
            ['name' => 'External Hard Drive', 'category_id' => 1, 'price' => 129, 'slug' => Str::slug('External Hard Drive'), 'description'=>'A reliable external hard drive for data storage.'],
            ['name' => 'Digital Camera', 'category_id' => 1, 'price' => 599, 'slug' => Str::slug('Digital Camera'), 'description'=>'A high-resolution digital camera suitable for photography enthusiasts.'],
            ['name' => 'Action Camera', 'category_id' => 1, 'price' => 299, 'slug' => Str::slug('Action Camera'), 'description'=>'A compact action camera suitable for capturing adventures.'],
        ];

        $product_of_clothing = [
            ['name' => 'T-Shirt', 'category_id' => 2, 'price' => 19, 'slug' => Str::slug('T-Shirt'), 'description'=>'A comfortable cotton T-shirt available in various sizes.'],
            ['name' => 'Jeans', 'category_id' => 2, 'price' => 49, 'slug' => Str::slug('Jeans'), 'description'=>'Stylish denim jeans with a perfect fit.'],
            ['name' => 'Jacket', 'category_id' => 2, 'price' => 99, 'slug' => Str::slug('Jacket'), 'description'=>'A stylish jacket perfect for cold weather.'],
            ['name' => 'Sweater', 'category_id' => 2, 'price' => 59, 'slug' => Str::slug('Sweater'), 'description'=>'A warm and cozy sweater for chilly days.'],
            ['name' => 'Dress', 'category_id' => 2, 'price' => 79, 'slug' => Str::slug('Dress'), 'description'=>'A fashionable dress suitable for various occasions.'],
            ['name' => 'Skirt', 'category_id' => 2, 'price' => 39, 'slug' => Str::slug('Skirt'), 'description'=>'A stylish skirt suitable for casual and formal occasions.'],
            ['name' => 'Coat', 'category_id' => 2, 'price' => 129, 'slug' => Str::slug('Coat'), 'description'=>'A warm coat perfect for winter.'],
            ['name' => 'Hat', 'category_id' => 2, 'price' => 29, 'slug' => Str::slug('Hat'), 'description'=>'A stylish hat suitable for various occasions.'],
            ['name' => 'Scarf', 'category_id' => 2, 'price' => 25, 'slug' => Str::slug('Scarf'), 'description'=>'A fashionable scarf to complement your outfit.'],
            ['name' => 'Gloves', 'category_id' => 2, 'price' => 19, 'slug' => Str::slug('Gloves'), 'description'=>'A pair of warm gloves suitable for winter.'],
        ];

        $product_of_book = [
            ['name' => 'The Great Gatsby', 'category_id' => 3, 'price' => 15, 'slug' => Str::slug('The Great Gatsby'), 'description'=>'A classic novel by F. Scott Fitzgerald.'],
            ['name' => '1984', 'category_id' => 3, 'price' => 12, 'slug' => Str::slug('1984'), 'description'=>'A dystopian novel by George Orwell.'],
            ['name' => 'To Kill a Mockingbird', 'category_id' => 3, 'price' => 18, 'slug' => Str::slug('To Kill a Mockingbird'), 'description'=>'A novel by Harper Lee addressing serious issues.'],
            ['name' => 'Moby-Dick', 'category_id' => 3, 'price' => 20, 'slug' => Str::slug('Moby-Dick'), 'description'=>'A classic novel by Herman Melville.'],
            ['name' => 'Pride and Prejudice', 'category_id' => 3, 'price' => 14, 'slug' => Str::slug('Pride and Prejudice'), 'description'=>'A romantic novel by Jane Austen.'],
            ['name' => 'The Catcher in the Rye', 'category_id' => 3, 'price' => 16, 'slug' => Str::slug('The Catcher in the Rye'), 'description'=>'A novel by J.D. Salinger exploring teenage angst and alienation.'],
            ['name' => 'Brave New World', 'category_id' => 3, 'price' => 17, 'slug' => Str::slug('Brave New World'), 'description'=>'A dystopian novel by Aldous Huxley.'],
            ['name' => 'The Hobbit', 'category_id' => 3, 'price' => 19, 'slug' => Str::slug('The Hobbit'), 'description'=>'A fantasy novel by J.R.R. Tolkien.'],
            ['name' => 'Harry Potter and the Sorcerer\'s Stone', 'category_id' => 3, 'price' => 22, 'slug' => Str::slug('Harry Potter and the Sorcerer\'s Stone'), 'description'=>'A fantasy novel by J.K. Rowling.'],
            ['name' => 'The Lord of the Rings', 'category_id' => 3, 'price' => 25, 'slug' => Str::slug('The Lord of the Rings'), 'description'=>'An epic fantasy novel by J.R.R. Tolkien.'],
        ];

        $product_of_home_kitchen = [
            ['name' => 'Blender', 'category_id' => 4, 'price' => 49, 'slug' => Str::slug('Blender'), 'description'=>'A versatile blender for your kitchen needs.'],
            ['name' => 'Microwave', 'category_id' => 4, 'price' => 99, 'slug' => Str::slug('Microwave'), 'description'=>'A convenient microwave for quick cooking.'],
            ['name' => 'Coffee Maker', 'category_id' => 4, 'price' => 79, 'slug' => Str::slug('Coffee Maker'), 'description'=>'A coffee maker for brewing fresh coffee.'],
            ['name' => 'Toaster', 'category_id' => 4, 'price' => 29, 'slug' => Str::slug('Toaster'), 'description'=>'A toaster for perfectly toasted bread.'],
            ['name' => 'Dishwasher', 'category_id' => 4, 'price' => 399, 'slug' => Str::slug('Dishwasher'), 'description'=>'A dishwasher for efficient cleaning of dishes.'],
            ['name' => 'Refrigerator', 'category_id' => 4, 'price' => 599, 'slug' => Str::slug('Refrigerator'), 'description'=>'A refrigerator for keeping your food fresh.'],
            ['name' => 'Air Fryer', 'category_id' => 4, 'price' => 129, 'slug' => Str::slug('Air Fryer'), 'description'=>'An air fryer for healthier frying options.'],
            ['name' => 'Slow Cooker', 'category_id' => 4, 'price' => 89, 'slug' => Str::slug('Slow Cooker'), 'description'=>'A slow cooker for convenient and flavorful meals.'],
            ['name' => 'Electric Kettle', 'category_id' => 4, 'price' => 39, 'slug' => Str::slug('Electric Kettle'), 'description'=>'An electric kettle for quickly boiling water.'],
            ['name' => 'Food Processor', 'category_id' => 4, 'price' => 149, 'slug' => Str::slug('Food Processor'), 'description'=>'A food processor for chopping, slicing, and dicing ingredients efficiently.'],
        ];

        $product_of_sports = [
            ['name' => 'Soccer Ball', 'category_id' => 5, 'price' => 25, 'slug' => Str::slug('Soccer Ball'), 'description'=>'A standard soccer ball for outdoor play.'],
            ['name' => 'Tennis Racket', 'category_id' => 5, 'price' => 75, 'slug' => Str::slug('Tennis Racket'), 'description'=>'A tennis racket for competitive and recreational play.'],
            ['name' => 'Basketball', 'category_id' => 5, 'price' => 30, 'slug' => Str::slug('Basketball'), 'description'=>'A basketball for indoor and outdoor games.'],
            ['name' => 'Running Shoes', 'category_id' => 5, 'price' => 120, 'slug' => Str::slug('Running Shoes'), 'description'=>'Comfortable running shoes for all terrains.'],
            ['name' => 'Yoga Mat', 'category_id' => 5, 'price' => 40, 'slug' => Str::slug('Yoga Mat'), 'description'=>'A non-slip yoga mat for your exercise routines.'],
            ['name' => 'Dumbbells', 'category_id' => 5, 'price' => 50, 'slug' => Str::slug('Dumbbells'), 'description'=>'A set of dumbbells for strength training exercises.'],
            ['name' => 'Exercise Bike', 'category_id' => 5, 'price' => 200, 'slug' => Str::slug('Exercise Bike'), 'description'=>'An exercise bike for indoor cardio workouts.'],
            ['name' => 'Treadmill', 'category_id' => 5, 'price' => 500, 'slug' => Str::slug('Treadmill'), 'description'=>'A treadmill for indoor running and walking workouts.'],
            ['name' => 'Pull-Up Bar', 'category_id' => 5, 'price' => 60, 'slug' => Str::slug('Pull-Up Bar'), 'description'=>'A pull-up bar for upper body strength training.'],
            ['name' => 'Resistance Bands', 'category_id' => 5, 'price' => 35, 'slug' => Str::slug('Resistance Bands'), 'description'=>'A set of resistance bands for versatile strength training exercises.'],
        ];

        $products = array_merge(
            $product_of_electronics,
            $product_of_home_kitchen,
            $product_of_sports,
            $product_of_clothing,
            $product_of_book,
        );

        foreach ($products as $product) {
            //check if the product already exists before inserting
            if (DB::table('products')->where('name', $product['name'])->exists()) {
                continue;
            }
            DB::table('products')->insert([
                'name' => $product['name'],
                'product_category_id' => $product['category_id'],
                'price' => rand(10000, 10000000),
                'slug' => $product['slug'],
                'stock' => rand(1, 100),
                'description' => $product['description'],
                'image' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

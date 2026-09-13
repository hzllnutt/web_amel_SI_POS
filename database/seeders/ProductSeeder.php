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
        $coffee = Category::where('category_name', 'Coffee')->first();
        $nonCoffee = Category::where('category_name', 'Non Coffee')->first();
        $tea = Category::where('category_name', 'Tea')->first();
        $food = Category::where('category_name', 'Food')->first();
        $snack = Category::where('category_name', 'Snack')->first();

        $products = [
            // Coffee Category
            [
                'category_id' => $coffee->id,
                'product_name' => 'Espresso',
                'product_price' => 18000,
                'product_description' => 'Single shot extracted premium Arabica beans with rich crema.',
                'product_stock' => 25,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Americano',
                'product_price' => 20000,
                'product_description' => 'Rich espresso diluted with hot or cold water, clean and bold.',
                'product_stock' => 30,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Cafe Latte',
                'product_price' => 25000,
                'product_description' => 'Smooth espresso combined with silky steamed milk and light foam.',
                'product_stock' => 20,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Cappuccino',
                'product_price' => 25000,
                'product_description' => 'Equal parts espresso, steamed milk, and velvety milk foam.',
                'product_stock' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Caramel Macchiato',
                'product_price' => 28000,
                'product_description' => 'Steamed milk with vanilla syrup, marked with espresso and caramel drizzle.',
                'product_stock' => 18,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Vanilla Latte',
                'product_price' => 27000,
                'product_description' => 'Espresso and steamed milk blended with aromatic Madagascar vanilla.',
                'product_stock' => 12,
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Mocha',
                'product_price' => 28000,
                'product_description' => 'Decadent chocolate sauce infused with espresso and fresh milk.',
                'product_stock' => 4, // Low stock <= 5
                'is_active' => true,
            ],
            [
                'category_id' => $coffee->id,
                'product_name' => 'Affogato',
                'product_price' => 24000,
                'product_description' => 'A scoop of creamy vanilla gelato drowned in a hot shot of espresso.',
                'product_stock' => 0, // SOLD OUT test
                'is_active' => true,
            ],

            // Non Coffee Category
            [
                'category_id' => $nonCoffee->id,
                'product_name' => 'Matcha Latte',
                'product_price' => 26000,
                'product_description' => 'Authentic Uji Japanese matcha whisked with fresh milk.',
                'product_stock' => 16,
                'is_active' => true,
            ],
            [
                'category_id' => $nonCoffee->id,
                'product_name' => 'Chocolate',
                'product_price' => 24000,
                'product_description' => 'Rich Belgian chocolate drink served hot or iced with cocoa dusting.',
                'product_stock' => 22,
                'is_active' => true,
            ],
            [
                'category_id' => $nonCoffee->id,
                'product_name' => 'Red Velvet Latte',
                'product_price' => 26000,
                'product_description' => 'Sweet and creamy red velvet drink with notes of vanilla and cacao.',
                'product_stock' => 10,
                'is_active' => true,
            ],
            [
                'category_id' => $nonCoffee->id,
                'product_name' => 'Taro Milk',
                'product_price' => 24000,
                'product_description' => 'Creamy fragrant taro flavor blended with cold milk.',
                'product_stock' => 3, // Low stock <= 5
                'is_active' => true,
            ],

            // Tea Category
            [
                'category_id' => $tea->id,
                'product_name' => 'Thai Tea',
                'product_price' => 20000,
                'product_description' => 'Classic Thai brewed black tea with condensed milk and evaporated milk.',
                'product_stock' => 25,
                'is_active' => true,
            ],
            [
                'category_id' => $tea->id,
                'product_name' => 'Earl Grey Tea',
                'product_price' => 18000,
                'product_description' => 'Classic black tea infused with fragrant oil of bergamot.',
                'product_stock' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $tea->id,
                'product_name' => 'Lemon Iced Tea',
                'product_price' => 18000,
                'product_description' => 'Refreshing black tea with fresh lemon slices and mint leaves.',
                'product_stock' => 20,
                'is_active' => true,
            ],
            [
                'category_id' => $tea->id,
                'product_name' => 'Peach Tea',
                'product_price' => 22000,
                'product_description' => 'Fruity artisan tea infused with sweet peach slices.',
                'product_stock' => 14,
                'is_active' => true,
            ],

            // Food Category
            [
                'category_id' => $food->id,
                'product_name' => 'Croissant Butter',
                'product_price' => 22000,
                'product_description' => 'Flaky, buttery French pastry freshly baked daily.',
                'product_stock' => 8,
                'is_active' => true,
            ],
            [
                'category_id' => $food->id,
                'product_name' => 'Sandwich Club',
                'product_price' => 32000,
                'product_description' => 'Toasted bread loaded with smoked beef, cheddar, egg, and fresh greens.',
                'product_stock' => 10,
                'is_active' => true,
            ],
            [
                'category_id' => $food->id,
                'product_name' => 'Spaghetti Aglio Olio',
                'product_price' => 35000,
                'product_description' => 'Al dente spaghetti with garlic, olive oil, chilli flakes, and grilled chicken.',
                'product_stock' => 12,
                'is_active' => true,
            ],
            [
                'category_id' => $food->id,
                'product_name' => 'Beef Burger',
                'product_price' => 38000,
                'product_description' => 'Juicy Australian beef patty, melted cheddar, lettuce, caramelized onions.',
                'product_stock' => 5, // Low stock <= 5
                'is_active' => true,
            ],

            // Snack Category
            [
                'category_id' => $snack->id,
                'product_name' => 'French Fries',
                'product_price' => 20000,
                'product_description' => 'Crispy golden shoestring potatoes with sea salt and garlic mayo.',
                'product_stock' => 30,
                'is_active' => true,
            ],
            [
                'category_id' => $snack->id,
                'product_name' => 'Chocolate Donut',
                'product_price' => 15000,
                'product_description' => 'Soft artisan donut topped with rich dark chocolate ganache.',
                'product_stock' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $snack->id,
                'product_name' => 'Tiramisu Cake',
                'product_price' => 28000,
                'product_description' => 'Espresso-soaked ladyfingers layered with mascarpone cheese mousse.',
                'product_stock' => 7,
                'is_active' => true,
            ],
            [
                'category_id' => $snack->id,
                'product_name' => 'Churros with Dulce de Leche',
                'product_price' => 23000,
                'product_description' => 'Spanish fried dough dusted in cinnamon sugar with caramel dipping sauce.',
                'product_stock' => 2, // Low stock <= 5
                'is_active' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['product_name' => $data['product_name']],
                $data
            );
        }
    }
}

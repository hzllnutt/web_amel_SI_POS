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
        $categories = [
            'Coffee',
            'Non Coffee',
            'Tea',
            'Food',
            'Snack',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['category_name' => $category]);
        }
    }
}

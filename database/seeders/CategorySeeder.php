<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Nhẫn', 'Vòng tay', 'Dây chuyền', 'Bông tai', 'Lắc tay'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

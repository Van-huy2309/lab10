<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestShopSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'khach@test.com'],
            [
                'name' => 'Khach Test',
                'password' => '12345678',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        User::where('email', 'admin@example.com')->update([
            'email_verified_at' => now(),
        ]);

        $this->call(CategorySeeder::class);

        $categories = Category::orderBy('id')->get();
        if ($categories->isEmpty()) {
            $categories = collect([Category::create(['name' => 'Trang sức'])]);
        }

        $products = [
            'Nhẫn bạc mini 10K',
            'Nhẫn titan trơn 10K',
            'Vòng tay hạt đá 10K',
            'Vòng tay dây da 10K',
            'Dây chuyền mặt tròn 10K',
            'Dây chuyền ngôi sao 10K',
            'Bông tai tròn nhỏ 10K',
            'Bông tai giọt nước 10K',
            'Lắc tay bi bạc 10K',
            'Lắc chân hạt nhỏ 10K',
        ];

        foreach ($products as $index => $name) {
            $category = $categories[$index % $categories->count()];

            $product = Product::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => $category->id,
                    'description' => 'Sản phẩm test lab, giá 10.000đ.',
                    'original_price' => 10000,
                    'voucher_percent' => 0,
                    'voucher_code' => null,
                    'is_active' => true,
                ]
            );

            if ($product->variants()->count() === 0) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => 'Bạc',
                    'color_hex' => '#c0c0c0',
                    'price' => 10000,
                    'stock' => 50,
                ]);
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => 'Vàng',
                    'color_hex' => '#d4af37',
                    'price' => 10000,
                    'stock' => 50,
                ]);
            }
        }
    }
}

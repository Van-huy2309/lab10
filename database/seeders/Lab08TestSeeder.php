<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Lab08TestSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(TestShopSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Shop',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $customers = collect([
            ['email' => 'khach@test.com', 'name' => 'Nguyễn Minh Anh', 'phone' => '0901234567'],
            ['email' => 'lan@test.com', 'name' => 'Trần Thị Lan', 'phone' => '0912345678'],
            ['email' => 'hung@test.com', 'name' => 'Lê Văn Hùng', 'phone' => '0987654321'],
        ])->map(function (array $row) {
            return User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => '12345678',
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );
        });

        $products = Product::with('variants')->where('is_active', true)->get();
        if ($products->isEmpty()) {
            $this->command?->warn('Chưa có sản phẩm — chạy TestShopSeeder trước.');

            return;
        }

        DB::transaction(function () use ($customers, $products) {
            PaymentTransaction::query()->delete();
            OrderItem::query()->delete();
            Order::query()->delete();

            $samples = [
                [
                    'user' => $customers[0],
                    'status' => 'pending',
                    'shipping_status' => 'pending',
                    'gateway' => 'momo',
                    'pay_status' => 'pending',
                    'days_ago' => 0,
                    'fee' => 25000,
                    'ghn' => null,
                ],
                [
                    'user' => $customers[0],
                    'status' => 'cod_ordered',
                    'shipping_status' => 'ready_to_pick',
                    'gateway' => 'cod',
                    'pay_status' => 'pending',
                    'days_ago' => 1,
                    'fee' => 30000,
                    'ghn' => 'TESTGHN001',
                ],
                [
                    'user' => $customers[1],
                    'status' => 'cod_ordered',
                    'shipping_status' => 'picking',
                    'gateway' => 'cod',
                    'pay_status' => 'pending',
                    'days_ago' => 2,
                    'fee' => 28000,
                    'ghn' => 'TESTGHN002',
                ],
                [
                    'user' => $customers[1],
                    'status' => 'paid_momo',
                    'shipping_status' => 'delivering',
                    'gateway' => 'momo',
                    'pay_status' => 'paid',
                    'days_ago' => 3,
                    'fee' => 32000,
                    'ghn' => 'TESTGHN003',
                ],
                [
                    'user' => $customers[2],
                    'status' => 'cod_paid',
                    'shipping_status' => 'delivered',
                    'gateway' => 'cod',
                    'pay_status' => 'paid',
                    'days_ago' => 5,
                    'fee' => 27000,
                    'ghn' => 'TESTGHN004',
                ],
                [
                    'user' => $customers[2],
                    'status' => 'paid_momo',
                    'shipping_status' => 'delivered',
                    'gateway' => 'momo',
                    'pay_status' => 'paid',
                    'days_ago' => 8,
                    'fee' => 35000,
                    'ghn' => 'TESTGHN005',
                ],
                [
                    'user' => $customers[0],
                    'status' => 'paid_momo',
                    'shipping_status' => 'delivered',
                    'gateway' => 'momo',
                    'pay_status' => 'paid',
                    'days_ago' => 12,
                    'fee' => 22000,
                    'ghn' => 'TESTGHN006',
                ],
                [
                    'user' => $customers[1],
                    'status' => 'cancelled',
                    'shipping_status' => 'cancelled',
                    'gateway' => 'cod',
                    'pay_status' => 'cancelled',
                    'days_ago' => 4,
                    'fee' => 25000,
                    'ghn' => null,
                ],
                [
                    'user' => $customers[2],
                    'status' => 'paid_momo',
                    'shipping_status' => 'returned',
                    'gateway' => 'momo',
                    'pay_status' => 'refund_pending',
                    'days_ago' => 10,
                    'fee' => 29000,
                    'ghn' => 'TESTGHN007',
                ],
                [
                    'user' => $customers[0],
                    'status' => 'cod_ordered',
                    'shipping_status' => 'not_shipped',
                    'gateway' => 'cod',
                    'pay_status' => 'pending',
                    'days_ago' => 0,
                    'fee' => 26000,
                    'ghn' => null,
                ],
            ];

            foreach ($samples as $i => $sample) {
                $picked = $products->random(min(2, $products->count()));
                $subtotal = 0;
                $createdAt = now()->subDays($sample['days_ago'])->subHours($i);

                $order = Order::create([
                    'user_id' => $sample['user']->id,
                    'name' => $sample['user']->name,
                    'address' => '123 Đường Nguyễn Huệ, Quận 1, TP.HCM',
                    'phone' => match ($sample['user']->email) {
                        'lan@test.com' => '0912345678',
                        'hung@test.com' => '0987654321',
                        default => '0901234567',
                    },
                    'total_price' => 0,
                    'status' => $sample['status'],
                    'shipping_status' => $sample['shipping_status'],
                    'ghn_order_code' => $sample['ghn'],
                    'ghn_total_fee' => $sample['fee'],
                    'to_district_id' => 1442,
                    'to_ward_code' => '21211',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($picked as $product) {
                    $variant = $product->variants->first();
                    $qty = rand(1, 3);
                    $price = (float) ($variant?->price ?? $product->original_price ?? 10000);
                    $subtotal += $price * $qty;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_variant_id' => $variant?->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'color' => $variant?->color,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }

                $order->update([
                    'total_price' => $subtotal + $sample['fee'],
                ]);

                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'gateway' => $sample['gateway'],
                    'amount' => $order->total_price,
                    'status' => $sample['pay_status'],
                    'message' => $sample['gateway'] === 'cod' ? 'Thanh toán khi nhận hàng' : 'Thanh toán MoMo test',
                    'paid_at' => $sample['pay_status'] === 'paid' ? $createdAt->copy()->addHour() : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        });

        $this->command?->info('Lab08: đã seed '.Order::count().' đơn hàng test.');
    }
}

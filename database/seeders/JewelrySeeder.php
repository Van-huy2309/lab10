<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class JewelrySeeder extends Seeder
{
    public function run(): void
    {
        // Tạo danh mục trang sức
        $categories = [
            ['name' => 'Nhẫn'],
            ['name' => 'Vòng tay'],
            ['name' => 'Dây chuyền'],
            ['name' => 'Bông tai'],
            ['name' => 'Lắc tay'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']]);
        }

        $catNhan   = Category::where('name', 'Nhẫn')->first();
        $catVong   = Category::where('name', 'Vòng tay')->first();
        $catDay    = Category::where('name', 'Dây chuyền')->first();
        $catBong   = Category::where('name', 'Bông tai')->first();
        $catLac    = Category::where('name', 'Lắc tay')->first();

        $products = [
            // Nhẫn
            [
                'category_id'     => $catNhan->id,
                'name'            => 'Nhẫn vàng 18K đính kim cương',
                'description'     => 'Nhẫn vàng 18K cao cấp, đính kim cương tự nhiên 0.3ct. Thiết kế tinh tế, phù hợp dùng làm nhẫn đính hôn hoặc quà tặng đặc biệt.',
                'original_price'  => 4500000,
                'voucher_percent' => 10,
                'voucher_code'    => 'NHAN10',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Vàng', 'color_hex' => '#d4af37', 'stock' => 15],
                    ['color' => 'Vàng hồng', 'color_hex' => '#e8b4b8', 'stock' => 10],
                    ['color' => 'Bạch kim', 'color_hex' => '#e8e8e8', 'stock' => 8],
                ],
            ],
            [
                'category_id'     => $catNhan->id,
                'name'            => 'Nhẫn bạc 925 mặt đá Ruby',
                'description'     => 'Nhẫn bạc 925 chính hãng với mặt đá Ruby đỏ rực rỡ. Phong cách thanh lịch, phù hợp cho mọi dịp.',
                'original_price'  => 890000,
                'voucher_percent' => 15,
                'voucher_code'    => 'RUBY15',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Bạc', 'color_hex' => '#c0c0c0', 'stock' => 25],
                    ['color' => 'Bạc mạ vàng', 'color_hex' => '#d4af37', 'stock' => 20],
                ],
            ],
            // Vòng tay
            [
                'category_id'     => $catVong->id,
                'name'            => 'Vòng tay ngọc trai Akoya',
                'description'     => 'Vòng tay được kết từ những viên ngọc trai Akoya thuần khiết. Kích thước ngọc 7-8mm, ánh trắng sáng tự nhiên.',
                'original_price'  => 2200000,
                'voucher_percent' => 0,
                'voucher_code'    => null,
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Trắng', 'color_hex' => '#f5f5f5', 'stock' => 12],
                    ['color' => 'Hồng nhạt', 'color_hex' => '#ffb6c1', 'stock' => 8],
                ],
            ],
            [
                'category_id'     => $catVong->id,
                'name'            => 'Vòng tay vàng đúc hoa mai',
                'description'     => 'Vòng tay vàng 14K đúc hoa mai tinh xảo. Biểu tượng may mắn và thịnh vượng, thích hợp làm quà tặng năm mới.',
                'original_price'  => 3800000,
                'voucher_percent' => 8,
                'voucher_code'    => 'VUONG8',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Vàng vàng', 'color_hex' => '#ffd700', 'stock' => 7],
                    ['color' => 'Vàng hồng', 'color_hex' => '#e8b4b8', 'stock' => 5],
                ],
            ],
            // Dây chuyền
            [
                'category_id'     => $catDay->id,
                'name'            => 'Dây chuyền bạc khắc tên',
                'description'     => 'Dây chuyền bạc 925 có thể khắc tên theo yêu cầu. Chiều dài dây 45cm, mặt hình trái tim. Quà tặng ý nghĩa cho người thân.',
                'original_price'  => 650000,
                'voucher_percent' => 0,
                'voucher_code'    => null,
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Bạc', 'color_hex' => '#c0c0c0', 'stock' => 30],
                    ['color' => 'Vàng hồng', 'color_hex' => '#e8b4b8', 'stock' => 20],
                ],
            ],
            [
                'category_id'     => $catDay->id,
                'name'            => 'Dây chuyền vàng 18K mặt phật',
                'description'     => 'Dây chuyền vàng 18K kèm mặt Phật Bà Quan Âm chạm khắc tinh xảo. Đem lại bình an và may mắn cho người đeo.',
                'original_price'  => 5200000,
                'voucher_percent' => 5,
                'voucher_code'    => 'PHAT5',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Vàng', 'color_hex' => '#d4af37', 'stock' => 10],
                ],
            ],
            // Bông tai
            [
                'category_id'     => $catBong->id,
                'name'            => 'Bông tai bạc đính đá Sapphire',
                'description'     => 'Bông tai bạc 925 đính đá Sapphire xanh lam tự nhiên. Kiểu dáng thanh thoát, phù hợp cho mọi khuôn mặt.',
                'original_price'  => 1200000,
                'voucher_percent' => 12,
                'voucher_code'    => 'BONG12',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Bạc - Sapphire xanh', 'color_hex' => '#0f52ba', 'stock' => 18],
                    ['color' => 'Bạc - Sapphire hồng', 'color_hex' => '#ff69b4', 'stock' => 14],
                ],
            ],
            [
                'category_id'     => $catBong->id,
                'name'            => 'Bông tai vàng hình giọt nước',
                'description'     => 'Bông tai vàng 10K hình giọt nước hiện đại. Nhẹ nhàng, tinh tế, phù hợp đi làm hoặc dự tiệc.',
                'original_price'  => 1850000,
                'voucher_percent' => 0,
                'voucher_code'    => null,
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Vàng vàng', 'color_hex' => '#ffd700', 'stock' => 15],
                    ['color' => 'Vàng hồng', 'color_hex' => '#e8b4b8', 'stock' => 10],
                    ['color' => 'Bạch kim', 'color_hex' => '#e8e8e8', 'stock' => 8],
                ],
            ],
            // Lắc tay
            [
                'category_id'     => $catLac->id,
                'name'            => 'Lắc tay vàng đúc 4 chỉ',
                'description'     => 'Lắc tay vàng 24K đúc 4 chỉ, trọng lượng 15.56g. Chứng nhận kiểm định rõ ràng, phù hợp làm của hồi môn.',
                'original_price'  => 28000000,
                'voucher_percent' => 3,
                'voucher_code'    => 'LAC3',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Vàng 24K', 'color_hex' => '#d4af37', 'stock' => 5],
                ],
            ],
            [
                'category_id'     => $catLac->id,
                'name'            => 'Lắc tay bạc charm thời trang',
                'description'     => 'Lắc tay bạc 925 với các charm dễ thương có thể tháo rời. Bộ sưu tập trẻ trung, cá tính cho giới trẻ.',
                'original_price'  => 480000,
                'voucher_percent' => 20,
                'voucher_code'    => 'CHARM20',
                'is_active'       => true,
                'variants' => [
                    ['color' => 'Bạc', 'color_hex' => '#c0c0c0', 'stock' => 40],
                    ['color' => 'Bạc mạ vàng hồng', 'color_hex' => '#e8b4b8', 'stock' => 35],
                    ['color' => 'Đen', 'color_hex' => '#2c2c2c', 'stock' => 20],
                ],
            ],
        ];

        foreach ($products as $data) {
            $variants = $data['variants'];
            unset($data['variants']);

            $product = Product::firstOrCreate(
                ['name' => $data['name'], 'category_id' => $data['category_id']],
                $data
            );

            if ($product->variants()->count() === 0) {
                foreach ($variants as $v) {
                    $product->variants()->create([
                        'color'     => $v['color'],
                        'color_hex' => $v['color_hex'],
                        'price'     => null,
                        'stock'     => $v['stock'],
                    ]);
                }
            }
        }
    }
}

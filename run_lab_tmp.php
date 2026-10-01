<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\User;
use App\Services\GHNService;
use Illuminate\Support\Facades\Hash;

$user = User::where('role', 'customer')->whereNotNull('email_verified_at')->first();
if (! $user) {
    $user = User::where('email', 'test.hdv@example.com')->first();
    if ($user) {
        $user->update(['email_verified_at' => now()]);
    } else {
        $user = User::create([
            'name' => 'Khach Test',
            'email' => 'test.order@example.com',
            'password' => 'password123',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}

$product = Product::with('variants')->where('is_active', true)->first();

echo 'USER=' . $user->email . PHP_EOL;
echo 'PRODUCT=' . ($product?->id ?? 'none') . ' ' . ($product?->name ?? '') . PHP_EOL;

$ghn = app(GHNService::class);
$provinces = $ghn->getProvinces();
echo 'PROVINCE_CODE=' . json_encode($provinces['code'] ?? $provinces) . PHP_EOL;
echo 'PROVINCE_MSG=' . ($provinces['message'] ?? '') . PHP_EOL;
echo 'PROVINCE_COUNT=' . (isset($provinces['data']) && is_array($provinces['data']) ? count($provinces['data']) : 0) . PHP_EOL;

if (! empty($provinces['data'])) {
    $hcm = collect($provinces['data'])->first(function ($p) {
        $name = $p['ProvinceName'] ?? '';
        return str_contains($name, 'Hồ Chí Minh') || str_contains($name, 'HCM');
    }) ?? $provinces['data'][0];
    echo 'PICK_PROVINCE=' . $hcm['ProvinceID'] . ' ' . $hcm['ProvinceName'] . PHP_EOL;

    $districts = $ghn->getDistricts((int) $hcm['ProvinceID']);
    echo 'DISTRICT_CODE=' . ($districts['code'] ?? '') . ' COUNT=' . (isset($districts['data']) ? count($districts['data']) : 0) . PHP_EOL;
    $district = $districts['data'][0] ?? null;
    if ($district) {
        echo 'PICK_DISTRICT=' . $district['DistrictID'] . ' ' . $district['DistrictName'] . PHP_EOL;
        $wards = $ghn->getWards((int) $district['DistrictID']);
        echo 'WARD_CODE=' . ($wards['code'] ?? '') . ' COUNT=' . (isset($wards['data']) ? count($wards['data']) : 0) . PHP_EOL;
        $ward = $wards['data'][0] ?? null;
        if ($ward) {
            echo 'PICK_WARD=' . $ward['WardCode'] . ' ' . $ward['WardName'] . PHP_EOL;
            $fee = $ghn->calculateFee(array_merge([
                'from_district_id' => (int) config('services.ghn.from_district_id'),
                'to_district_id' => (int) $district['DistrictID'],
                'to_ward_code' => (string) $ward['WardCode'],
            ], $ghn->packageParameters(200)));
            echo 'FEE=' . json_encode($fee, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        }
    }
}

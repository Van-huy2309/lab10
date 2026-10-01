<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GHNService
{
    protected string $baseUrl;

    protected string $token;

    protected int $shopId;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.ghn.base_url');
        $this->token = (string) (config('services.ghn.token') ?? '');
        $this->shopId = (int) config('services.ghn.shop_id', 0);
    }

    public function productWeight(): int
    {
        return (int) config('services.ghn.default_weight', 200);
    }

    public function packageParameters(int $weight): array
    {
        return [
            'service_type_id' => 2,
            'weight' => max($weight, $this->productWeight()),
            'length' => 15,
            'width' => 15,
            'height' => 10,
            'insurance_value' => 0,
        ];
    }

    protected function client(bool $withShop = false)
    {
        $headers = [
            'Token' => $this->token,
            'Content-Type' => 'application/json',
        ];

        if ($withShop && $this->shopId > 0) {
            $headers['ShopId'] = (string) $this->shopId;
        }

        return Http::baseUrl($this->baseUrl)
            ->withOptions([
                'verify' => filter_var(config('services.ghn.verify_ssl', true), FILTER_VALIDATE_BOOLEAN),
            ])
            ->acceptJson()
            ->timeout(12)
            ->withHeaders($headers);
    }

    public function getProvinces(): array
    {
        return $this->rememberMaster('ghn.provinces', fn () => $this->get('/master-data/province'));
    }

    public function getDistricts(int $provinceId): array
    {
        return $this->rememberMaster("ghn.districts.{$provinceId}", fn () => $this->post('/master-data/district', [
            'province_id' => $provinceId,
        ]));
    }

    public function getWards(int $districtId): array
    {
        return $this->rememberMaster("ghn.wards.{$districtId}", fn () => $this->post('/master-data/ward', [
            'district_id' => $districtId,
        ]));
    }

    protected function rememberMaster(string $key, callable $loader): array
    {
        $cached = Cache::store('file')->get($key);
        if (is_array($cached) && ! empty($cached['data'])) {
            return $cached;
        }

        $result = $loader();
        if (! empty($result['data'])) {
            Cache::store('file')->put($key, $result, 43200);
        }

        return $result;
    }

    public function calculateFee(array $params): array
    {
        return $this->post('/v2/shipping-order/fee', array_merge([
            'shop_id' => $this->shopId,
        ], $params), true);
    }

    public function createOrder(array $orderData): array
    {
        return $this->post('/v2/shipping-order/create', array_merge([
            'shop_id' => $this->shopId,
        ], $orderData), true);
    }

    public function cancelOrder(array $orderCodes): array
    {
        return $this->post('/v2/switch-status/cancel', [
            'order_codes' => $orderCodes,
            'shop_id' => $this->shopId,
        ], true);
    }

    protected function get(string $uri, array $query = [], bool $withShop = false): array
    {
        try {
            $response = $this->client($withShop)->get($uri, $query);

            return $this->decode($response, 'GET', $uri);
        } catch (ConnectionException $exception) {
            Log::error('Unable to connect to GHN', ['uri' => $uri, 'error' => $exception->getMessage()]);

            return ['code' => -1, 'message' => 'Unable to connect to GHN.'];
        }
    }

    protected function post(string $uri, array $payload, bool $withShop = false): array
    {
        try {
            $response = $this->client($withShop)->post($uri, $payload);

            return $this->decode($response, 'POST', $uri);
        } catch (ConnectionException $exception) {
            Log::error('Unable to connect to GHN', ['uri' => $uri, 'error' => $exception->getMessage()]);

            return ['code' => -1, 'message' => 'Unable to connect to GHN.'];
        }
    }

    protected function decode($response, string $method, string $uri): array
    {
        $json = $response->json();

        if (! $response->successful() || empty($json['data'])) {
            Log::warning("GHN {$method} request failed", [
                'uri' => $uri,
                'status' => $response->status(),
                'body' => $json,
            ]);
        }

        return $json ?? ['code' => $response->status(), 'message' => 'GHN API request failed.'];
    }
}

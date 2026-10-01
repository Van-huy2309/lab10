<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $totalPrice = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('user.cart.index', compact('cart', 'totalPrice'));
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
            'variant_id' => 'nullable|integer',
        ]);

        abort_unless($product->is_active, 404);

        $variant = null;
        $price = (float) $product->sale_price;
        $color = null;

        if ($product->variants()->exists()) {
            $request->validate([
                'variant_id' => 'required|integer|exists:product_variants,id',
            ]);
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $data['variant_id'])
                ->firstOrFail();
            $price = $variant->price ? (float) $variant->price : $price;
            $color = $variant->color;
        }

        $key = $product->id . '-' . ($variant?->id ?? 0);
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += (int) $data['quantity'];
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'variant_id' => $variant?->id,
                'name' => $product->name,
                'color' => $color,
                'price' => $price,
                'quantity' => (int) $data['quantity'],
                'weight' => 200,
                'image' => $variant?->imageUrl() ?: $product->imageUrl(),
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('user.cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, string $key)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        $cart = session('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $data['quantity'];
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}

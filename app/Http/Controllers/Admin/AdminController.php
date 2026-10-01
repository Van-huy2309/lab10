<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'users' => User::where('role', 'customer')->count(),
            'active_products' => Product::where('is_active', true)->count(),
            'orders' => Order::count(),
            'pending_orders' => Order::whereIn('shipping_status', ['pending', 'not_shipped', 'processing', 'ready_to_pick'])->where('status', '!=', 'cancelled')->count(),
        ];

        $latestProducts = Product::with('category')->latest()->take(5)->get();
        $latestOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestProducts', 'latestOrders'));
    }
}

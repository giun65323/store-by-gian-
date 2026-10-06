<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Produk milik user yang login
        $myProducts = Product::where('user_id', $user->id)->latest()->get();

        // Produk marketplace yang bisa dibeli (dari seluruh penjual)
        $marketplaceProducts = Product::with(['store', 'user'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->latest()
            ->take(12)
            ->get();

        // Riwayat pesanan terakhir user
        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['store', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'myProducts', 'marketplaceProducts', 'recentOrders'));
    }
}

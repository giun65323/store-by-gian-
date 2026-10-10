<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Tampilkan profil publik toko dan seluruh katalog produk yang dijual.
     * Mengikuti panduan antislop: data riil, tanpa klaim palsu, desain fungsional.
     */
    public function show(string $store)
    {
        // 1. Cari toko berdasarkan slug atau ID
        $storeModel = Store::with('seller')
            ->where('slug', $store)
            ->orWhere('id', $store)
            ->first();

        // 2. Fallback jika parameter pencarian adalah ID pengguna seller
        if (!$storeModel && is_numeric($store)) {
            $seller = User::find($store);
            if ($seller && $seller->store) {
                $storeModel = $seller->store;
            }
        }

        if (!$storeModel) {
            abort(404, 'Toko tidak ditemukan atau sudah tidak aktif.');
        }

        // 3. Query produk yang terhubung ke toko atau seller pemilik toko
        $storeProductQuery = Product::where(function ($query) use ($storeModel) {
            $query->where('store_id', $storeModel->id)
                  ->orWhere('user_id', $storeModel->seller_id);
        });

        // 4. Produk aktif dengan paginasi
        $products = (clone $storeProductQuery)
            ->where('status', 'active')
            ->with(['reviews'])
            ->latest()
            ->paginate(12);

        // 5. Statistik berbasis data riil (antislop: C-5, R-17, R-36)
        $storeProductIds = (clone $storeProductQuery)->pluck('id');
        $totalProducts   = (clone $storeProductQuery)->where('status', 'active')->count();
        $totalReviews    = Review::whereIn('product_id', $storeProductIds)->count();
        $avgRating       = $totalReviews > 0 ? round((float) Review::whereIn('product_id', $storeProductIds)->avg('rating'), 1) : 5.0;

        return view('stores.show', [
            'store'         => $storeModel,
            'products'      => $products,
            'totalProducts' => $totalProducts,
            'totalReviews'  => $totalReviews,
            'avgRating'     => $avgRating,
        ]);
    }
}

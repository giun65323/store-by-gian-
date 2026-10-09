<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika user adalah ADMIN GIAN GAMES, tampilkan semua produk dari seluruh penjual
        if ($user->isAdmin()) {
            $products = Product::with(['user', 'store'])
                ->latest()
                ->paginate(12);
        } else {
            // Jika seller biasa, hanya tampilkan produk milik sendiri
            $products = Product::where('user_id', $user->id)
                ->latest()
                ->paginate(12);
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isSeller() && !$user->isAdmin()) {
            return redirect()->route('become-seller')
                ->with('error', 'Akun Anda saat ini bertipe Buyer. Silakan ajukan pendaftaran Seller untuk menambah produk.');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSeller() && !$user->isAdmin()) {
            return redirect()->route('become-seller')
                ->with('error', 'Akun Anda saat ini bertipe Buyer. Silakan ajukan pendaftaran Seller untuk menambah produk.');
        }

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'delivery_type' => ['required', 'in:instant,regular'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['store_id'] = auth()->user()->store?->id;
        $validated['status'] = 'active';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $this->authorizeOwnerOrAdmin($product);

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeOwnerOrAdmin($product);

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:5000'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'delivery_type' => ['required', 'in:instant,regular'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'status'        => ['nullable', 'in:active,suspended,deleted_by_admin'],
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) {
            // User mencentang "hapus foto"
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeOwnerOrAdmin($product);

        $productName = $product->name;

        // Hapus gambar produk jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Jika Admin yang menghapus, tandai status
        if (auth()->user()->isAdmin()) {
            $product->update(['status' => 'deleted_by_admin']);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', "Produk '{$productName}' berhasil dihapus.");
    }

    /**
     * Tampilkan detail produk secara publik beserta deskripsi & ulasan bintang.
     */
    public function show(Product $product)
    {
        // Cek jika status non-aktif & bukan admin/pemilik
        if (!$product->isActive()) {
            $user = auth()->user();
            if (!$user || (!$user->isAdmin() && $user->id !== $product->user_id)) {
                abort(404, 'Produk tidak ditemukan atau sedang tidak aktif.');
            }
        }

        $product->load([
            'user',
            'store',
            'reviews' => function ($query) {
                $query->with('user')->latest();
            }
        ]);

        $totalReviews = $product->reviews->count();
        $avgRating = $totalReviews > 0 ? round((float) $product->reviews->avg('rating'), 1) : 5.0;

        // Hitung distribusi bintang 1-5
        $starCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];
        foreach ($product->reviews as $rev) {
            if (isset($starCounts[$rev->rating])) {
                $starCounts[$rev->rating]++;
            }
        }

        $ratingBreakdown = [];
        foreach ($starCounts as $stars => $count) {
            $ratingBreakdown[$stars] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
            ];
        }

        // Produk rekomendasi lainnya
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $userReview = auth()->check()
            ? $product->reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('products.show', compact(
            'product',
            'totalReviews',
            'avgRating',
            'ratingBreakdown',
            'relatedProducts',
            'userReview'
        ));
    }

    /**
     * Simpan / perbarui review dan rating bintang dari pembeli.
     */
    public function storeReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $existingReview = $product->reviews()->where('user_id', auth()->id())->first();

        if ($existingReview) {
            $existingReview->update([
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);
            $message = 'Ulasan dan penilaian bintang Anda berhasil diperbarui!';
        } else {
            $product->reviews()->create([
                'user_id' => auth()->id(),
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);
            $message = 'Terima kasih! Ulasan dan penilaian bintang Anda berhasil disimpan.';
        }

        return redirect()->route('products.show', $product)->with('success', $message);
    }

    /**
     * Hapus review produk (oleh pemilik review atau Admin).
     */
    public function destroyReview(Review $review)
    {
        if (auth()->id() !== $review->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin menghapus ulasan ini.');
        }

        $productId = $review->product_id;
        $review->delete();

        return redirect()->route('products.show', $productId)->with('success', 'Ulasan berhasil dihapus.');
    }

    /**
     * Pastikan produk ini milik user yang sedang login ATAU user adalah Admin gian games.
     */
    private function authorizeOwnerOrAdmin(Product $product): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        // Admin gian games memiliki izin penuh menghapus/mengubah barang siapapun
        if ($user->isAdmin()) {
            return;
        }

        // Seller hanya bisa mengelola barang miliknya sendiri
        abort_unless($product->user_id === $user->id, 403, 'Akses Ditolak. Hanya Admin gian games atau pemilik barang yang diizinkan mengelola produk ini.');
    }
}

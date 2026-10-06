<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    /**
     * Ambil data keranjang saat ini (JSON).
     */
    public function getCart()
    {
        $cart = session()->get('shopping_cart', []);

        $items = [];
        $totalPrice = 0;
        $totalQuantity = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with('store', 'user')->find($productId);
            if ($product && $product->stock > 0) {
                $qty = min($item['quantity'], $product->stock);
                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;
                $totalQuantity += $qty;

                $imageUrl = null;
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    $imageUrl = asset('storage/' . $product->image);
                }

                $items[] = [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'price'        => (float) $product->price,
                    'price_format' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'quantity'     => $qty,
                    'max_stock'    => $product->stock,
                    'subtotal'     => $subtotal,
                    'subtotal_fmt' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                    'store_name'   => $product->store ? $product->store->name : ($product->user?->name ?? 'Toko'),
                    'image'        => $imageUrl,
                ];
            } else {
                // Hapus jika produk sudah tidak ada atau stok 0
                unset($cart[$productId]);
            }
        }

        session()->put('shopping_cart', $cart);

        return response()->json([
            'status'         => 'success',
            'count'          => $totalQuantity,
            'total_price'    => $totalPrice,
            'total_price_fmt'=> 'Rp ' . number_format($totalPrice, 0, ',', '.'),
            'items'          => $items,
        ]);
    }

    /**
     * Tambahkan produk ke keranjang.
     */
    public function add(Request $request, Product $product)
    {
        if ($product->stock <= 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stok produk sedang habis.',
            ], 422);
        }

        if ($product->user_id === Auth::id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak dapat menambahkan produk toko sendiri ke keranjang.',
            ], 422);
        }

        $cart = session()->get('shopping_cart', []);
        $qtyToAdd = (int) $request->input('quantity', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min($cart[$product->id]['quantity'] + $qtyToAdd, $product->stock);
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'quantity'   => min($qtyToAdd, $product->stock),
            ];
        }

        session()->put('shopping_cart', $cart);

        return $this->getCart();
    }

    /**
     * Update jumlah quantity produk di keranjang.
     */
    public function update(Request $request, Product $product)
    {
        $cart = session()->get('shopping_cart', []);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } elseif (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min($quantity, $product->stock);
        }

        session()->put('shopping_cart', $cart);

        return $this->getCart();
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(Product $product)
    {
        $cart = session()->get('shopping_cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('shopping_cart', $cart);
        }

        return $this->getCart();
    }

    /**
     * Kosongkan keranjang.
     */
    public function clear()
    {
        session()->forget('shopping_cart');

        return response()->json([
            'status'          => 'success',
            'count'           => 0,
            'total_price'     => 0,
            'total_price_fmt' => 'Rp 0',
            'items'           => [],
        ]);
    }

    /**
     * Checkout seluruh barang di keranjang menjadi pesanan nyata.
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('shopping_cart', []);

        if (empty($cart)) {
            return back()->withErrors(['error' => 'Keranjang Anda masih kosong.']);
        }

        $user = Auth::user();
        $paymentMethod = $request->input('payment_method', 'DANA');
        $createdOrders = [];

        DB::transaction(function () use ($cart, $user, $paymentMethod, &$createdOrders) {
            foreach ($cart as $productId => $item) {
                $product = Product::lockForUpdate()->find($productId);
                if (!$product || $product->stock <= 0) {
                    continue;
                }

                $qty = min($item['quantity'], $product->stock);
                $product->decrement('stock', $qty);

                $orderNumber = Order::generateOrderNumber();

                $deliveryType = ($product->delivery_type === 'regular') ? 'Pengiriman BIASA' : 'Pengiriman INSTAN';

                $order = Order::create([
                    'order_number'   => $orderNumber,
                    'buyer_id'       => $user->id,
                    'seller_id'      => $product->user_id,
                    'store_id'       => $product->store_id ?? $product->user?->store?->id,
                    'product_id'     => $product->id,
                    'product_name'   => $product->name,
                    'product_image'  => $product->image,
                    'category_name'  => $product->store ? $product->store->name : 'Produk Game & Digital',
                    'price'          => $product->price,
                    'quantity'       => $qty,
                    'total_price'    => $product->price * $qty,
                    'status'         => 'pending', // Menunggu Pembayaran
                    'voucher_code'   => null,
                    'payment_method' => $paymentMethod,
                    'delivery_type'  => $deliveryType,
                ]);

                $createdOrders[] = $order;
            }

            // Kosongkan keranjang setelah berhasil checkout
            session()->forget('shopping_cart');
        });

        if (count($createdOrders) >= 1) {
            return redirect()->route('orders.payment', $createdOrders[0]->order_number)
                ->with('info', 'Silakan pilih metode pembayaran (DANA, GoPay, QRIS) untuk menyelesaikan pesanan.');
        }

        return redirect()->route('dashboard')->withErrors(['error' => 'Gagal memproses pesanan karena stok habis.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar riwayat transaksi pesanan user.
     */
    public function index()
    {
        $userId = Auth::id();

        // Pesanan yang dibeli oleh user ini
        $myPurchases = Order::where('buyer_id', $userId)
            ->with(['store', 'seller', 'product'])
            ->latest()
            ->paginate(10, ['*'], 'purchases_page');

        // Pesanan masuk toko (jika user adalah seller)
        $mySales = Order::where('seller_id', $userId)
            ->with(['buyer', 'product'])
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        return view('orders.index', compact('myPurchases', 'mySales'));
    }

    /**
     * Tampilkan halaman Order Details spesifik (sesuai tampilan Itemku).
     */
    public function show(string $order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['store', 'seller', 'buyer', 'product.reviews', 'messages.sender'])
            ->firstOrFail();

        // Pastikan hanya pihak terkait (buyer, seller, atau admin) yang bisa melihat
        $user = Auth::user();
        if (!$user->isAdmin() && $user->id !== $order->buyer_id && $user->id !== $order->seller_id) {
            abort(403, 'Anda tidak memiliki akses ke rincian pesanan ini.');
        }

        $userReview = ($user && $order->product)
            ? $order->product->reviews->firstWhere('user_id', $user->id)
            : null;

        return view('orders.show', compact('order', 'userReview'));
    }

    /**
     * Cari transaksi berdasarkan Order Number (misal: OD000000164288180).
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
        ]);

        $query = trim($request->input('order_number'));

        // Cek jika nomor order persis
        $order = Order::where('order_number', $query)->first();

        // Jika tidak ketemu langsung, coba pencarian LIKE / case-insensitive
        if (!$order) {
            $order = Order::where('order_number', 'LIKE', "%{$query}%")->first();
        }

        if ($order) {
            return redirect()->route('orders.show', $order->order_number);
        }

        return back()->withErrors([
            'order_search' => "Nomor pesanan '{$query}' tidak ditemukan. Pastikan format nomor pesanan benar (contoh: OD000000164288180)."
        ])->withInput();
    }

    /**
     * Tampilkan halaman Pembayaran (DANA, GoPay, QRIS, dll).
     */
    public function payment(string $order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['store', 'seller', 'buyer', 'product'])
            ->firstOrFail();

        $user = Auth::user();
        if (!$user->isAdmin() && $user->id !== $order->buyer_id) {
            abort(403, 'Anda tidak memiliki izin mengakses pembayaran pesanan ini.');
        }

        if ($order->status === 'completed') {
            return redirect()->route('orders.show', $order->order_number)
                ->with('info', 'Pesanan ini sudah berhasil dibayar.');
        }

        return view('orders.payment', compact('order'));
    }

    /**
     * Proses Konfirmasi Pembayaran Pesanan (DANA, GoPay, QRIS).
     */
    public function processPayment(Request $request, string $order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['store', 'seller', 'buyer', 'product'])
            ->firstOrFail();

        $user = Auth::user();
        if (!$user->isAdmin() && $user->id !== $order->buyer_id) {
            abort(403, 'Anda tidak memiliki izin memproses pembayaran pesanan ini.');
        }

        $paymentMethod = $request->input('payment_method', 'QRIS');

        if ($order->status === 'pending') {
            $isInstant = ($order->delivery_type === 'Pengiriman INSTAN' || ($order->product && $order->product->delivery_type !== 'regular'));

            if ($isInstant) {
                // PESANAN INSTAN: Langsung selesai dan voucher otomatis terbit
                $voucherCode = $order->voucher_code ?: Order::generateVoucherCode();

                $order->update([
                    'status'         => 'completed',
                    'voucher_code'   => $voucherCode,
                    'payment_method' => $paymentMethod,
                ]);

                // Kirim pesan ke chat rincian pesanan
                $order->messages()->create([
                    'sender_id'   => $order->seller_id,
                    'receiver_id' => $user->id,
                    'message'     => "⚡ [PESANAN INSTAN] Halo {$user->name}! Pembayaran via {$paymentMethod} telah berhasil dikonfirmasi. Kode voucher instan Anda: {$voucherCode}. Terima kasih telah berbelanja!",
                    'is_read'     => false,
                ]);

                return redirect()->route('orders.show', $order->order_number)
                    ->with('success', "Pembayaran via {$paymentMethod} Berhasil! Pesanan INSTAN Anda telah selesai dan kode voucher sudah aktif.");
            } else {
                // PESANAN BIASA (MANUAL): Status menjadi 'processing' (Menunggu Pengiriman Penjual)
                $order->update([
                    'status'         => 'processing',
                    'payment_method' => $paymentMethod,
                ]);

                // Kirim pesan otomatis bahwa pesanan sedang diproses penjual
                $order->messages()->create([
                    'sender_id'   => $order->seller_id,
                    'receiver_id' => $user->id,
                    'message'     => "📦 [PESANAN BIASA] Halo {$user->name}! Pembayaran via {$paymentMethod} telah diterima. Penjual sedang menyiapkan dan memproses pesanan Anda. Anda dapat berkoordinasi langsung melalui chat ini.",
                    'is_read'     => false,
                ]);

                return redirect()->route('orders.show', $order->order_number)
                    ->with('success', "Pembayaran via {$paymentMethod} Berhasil! Pesanan BIASA Anda telah diteruskan dan sedang diproses oleh penjual.");
            }
        }

        return redirect()->route('orders.show', $order->order_number);
    }

    /**
     * Seller mengkonfirmasi pengiriman pesanan biasa (Regular Order).
     */
    public function deliver(Request $request, string $order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['store', 'seller', 'buyer', 'product'])
            ->firstOrFail();

        $user = Auth::user();
        if (!$user->isAdmin() && $user->id !== $order->seller_id) {
            abort(403, 'Hanya penjual atau admin yang dapat mengkonfirmasi pengiriman pesanan ini.');
        }

        if ($order->status !== 'processing') {
            return back()->with('info', 'Status pesanan saat ini tidak dalam proses pengiriman.');
        }

        $note = trim($request->input('delivery_note', ''));
        $voucherData = $note ?: 'Pesanan telah dikirimkan oleh penjual';

        $order->update([
            'status'       => 'completed',
            'voucher_code' => $voucherData,
        ]);

        // Kirim pesan konfirmasi pengiriman ke chat
        $order->messages()->create([
            'sender_id'   => $user->id,
            'receiver_id' => $order->buyer_id,
            'message'     => "✅ [PESANAN DIKIRIM] Halo {$order->buyer?->name}! Pesanan Anda telah selesai kami kirimkan." . ($note ? "\nCatatan/Data: {$note}" : "") . "\nTerima kasih telah berbelanja!",
            'is_read'     => false,
        ]);

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dikonfirmasi dan ditandai sebagai Terkirim/Selesai!');
    }

    /**
     * Beli / Checkout Instant suatu Produk -> Masuk ke Tahap Pembayaran.
     */
    public function buy(Request $request, Product $product)
    {
        if ($product->stock <= 0) {
            return back()->withErrors(['error' => 'Maaf, stok produk ini sedang habis.']);
        }

        $user = Auth::user();

        // Tidak boleh membeli produk toko sendiri
        if ($product->user_id === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak dapat membeli produk dari toko Anda sendiri.']);
        }

        $paymentMethod = $request->input('payment_method', 'QRIS');

        $order = DB::transaction(function () use ($product, $user, $paymentMethod) {
            // 1. Kurangi stok
            $product->decrement('stock', 1);

            // 2. Generate Random Order Number
            $orderNumber = Order::generateOrderNumber();

            $deliveryType = ($product->delivery_type === 'regular') ? 'Pengiriman BIASA' : 'Pengiriman INSTAN';

            // 3. Simpan Pesanan dengan status 'pending' (Menunggu Pembayaran)
            $newOrder = Order::create([
                'order_number'   => $orderNumber,
                'buyer_id'       => $user->id,
                'seller_id'      => $product->user_id,
                'store_id'       => $product->store_id ?? $product->user?->store?->id,
                'product_id'     => $product->id,
                'product_name'   => $product->name,
                'product_image'  => $product->image,
                'category_name'  => $product->store ? $product->store->name : 'Produk Digital',
                'price'          => $product->price,
                'quantity'       => 1,
                'total_price'    => $product->price,
                'status'         => 'pending', // TAHAP PEMBAYARAN
                'voucher_code'   => null,     // Belum dirilis sebelum bayar
                'payment_method' => $paymentMethod,
                'delivery_type'  => $deliveryType,
            ]);

            return $newOrder;
        });

        // Arahkan ke halaman pemilihan metode pembayaran (DANA, GoPay, QRIS)
        return redirect()->route('orders.payment', $order->order_number);
    }
}

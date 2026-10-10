<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Fallback untuk serving file storage publik (gambar/avatar/produk) jika web server belum membaca symlink
Route::get('/storage/{path}', function (string $path) {
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.fallback');

// ==== Pusat Bantuan (Help Center & AI Assistant) ====
Route::get('/help-center', [HelpCenterController::class, 'index'])->name('help.index');
Route::post('/help-center/ai-chat', [HelpCenterController::class, 'chatAi'])->name('help.ai.chat');

// ==== Detail Produk Publik & Ulasan Bintang ====
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->whereNumber('product');

// ==== Profil Toko & Katalog Dagangan Penjual ====
Route::get('/stores/{store}', [App\Http\Controllers\StoreController::class, 'show'])->name('stores.show');

// ==== Guest only (belum login) ====
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verify-otp');
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);

    // ==== Reset Password via OTP (Gmail) ====
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password/otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
    Route::post('/reset-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.otp');
    Route::post('/reset-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend');
    Route::get('/reset-password/new', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.new');
    Route::post('/reset-password/update', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

// ==== Auth only (sudah login) ====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==== User Profile (Edit Profil & Database) ====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ==== Upgrade Buyer → Seller ====
    Route::get('/become-seller', [RegisterController::class, 'showBecomeSeller'])->name('become-seller');
    Route::post('/become-seller', [RegisterController::class, 'becomeSeller'])->name('become-seller.submit');
    Route::get('/become-seller/verify-otp', [RegisterController::class, 'showBecomeSellerVerifyOtpForm'])->name('become-seller.verify-otp');
    Route::post('/become-seller/verify-otp', [RegisterController::class, 'verifyBecomeSellerOtp'])->name('become-seller.verify-otp.submit');
    Route::post('/become-seller/resend-otp', [RegisterController::class, 'resendBecomeSellerOtp'])->name('become-seller.resend-otp');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('/products/{product}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');
    Route::delete('/reviews/{review}', [ProductController::class, 'destroyReview'])->name('reviews.destroy');

    // ==== Orders / Transaksi & Pencarian ====
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/search', [OrderController::class, 'search'])->name('orders.search');
    Route::post('/products/{product}/buy', [OrderController::class, 'buy'])->name('orders.buy');
    Route::get('/orders/{order_number}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order_number}/pay', [OrderController::class, 'processPayment'])->name('orders.pay');
    Route::post('/orders/{order_number}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::get('/orders/{order_number}', [OrderController::class, 'show'])->name('orders.show');

    // ==== Chat Pembeli & Penjual (Live Chat & Notifikasi) ====
    Route::get('/orders/{order_number}/chat/messages', [OrderChatController::class, 'fetchMessages'])->name('orders.chat.fetch');
    Route::post('/orders/{order_number}/chat/send', [OrderChatController::class, 'sendMessage'])->name('orders.chat.send');
    Route::get('/chat/notifications', [OrderChatController::class, 'notifications'])->name('orders.chat.notifications');
    // ==== Shopping Cart (Keranjang Belanja) ====
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    // ==== KHUSUS ADMIN GIAN GAMES (Full Control & Moderasi) ====
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        Route::post('/products/{product}/status', [AdminController::class, 'toggleProductStatus'])->name('products.status');
        Route::post('/stores/{store}/toggle', [AdminController::class, 'toggleStoreStatus'])->name('stores.toggle');
        Route::delete('/stores/{store}', [AdminController::class, 'deleteStore'])->name('stores.delete');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::post('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
        Route::post('/users/{user}/approve-seller', [AdminController::class, 'approveSeller'])->name('users.approve-seller');
        Route::post('/users/{user}/reject-seller', [AdminController::class, 'rejectSeller'])->name('users.reject-seller');
        Route::post('/users/{user}/revoke-seller', [AdminController::class, 'revokeSeller'])->name('users.revoke-seller');
    });
});

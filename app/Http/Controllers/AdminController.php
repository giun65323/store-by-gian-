<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Tampilkan Pusat Kontrol Admin gian games.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalStores   = Store::count();
        $totalUsers    = User::count();
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('status', 'completed')->sum('total_price');

        // Daftar semua produk dengan relasi toko dan user penjual
        $allProducts = Product::with(['store', 'user'])
            ->latest()
            ->paginate(15, ['*'], 'products_page');

        // Daftar semua toko
        $allStores = Store::with(['seller', 'products'])
            ->latest()
            ->paginate(10, ['*'], 'stores_page');

        // Daftar semua pengguna
        $allUsers = User::with(['role', 'store'])
            ->latest()
            ->paginate(15, ['*'], 'users_page');

        // Pendaftaran seller yang menunggu verifikasi
        $pendingSellers = User::where('seller_status', 'pending')
            ->latest('seller_applied_at')
            ->get();

        // Daftar role sistem
        $roles = Role::all();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStores',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'allProducts',
            'allStores',
            'allUsers',
            'pendingSellers',
            'roles'
        ));
    }

    /**
     * Admin: Hapus produk milik seller manapun.
     */
    public function deleteProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $productName = $product->name;
        $product->update(['status' => 'deleted_by_admin']);
        $product->delete();

        return back()->with('success', "Produk '{$productName}' berhasil dihapus oleh Admin gian games.");
    }

    /**
     * Admin: Ubah status produk (active, suspended, deleted_by_admin).
     */
    public function toggleProductStatus(Request $request, Product $product)
    {
        $newStatus = $request->input('status', 'suspended');

        $product->update(['status' => $newStatus]);

        return back()->with('success', "Status produk '{$product->name}' diubah menjadi {$newStatus}.");
    }

    /**
     * Admin: Suspend atau Aktifkan Toko seller.
     */
    public function toggleStoreStatus(Request $request, Store $store)
    {
        $newStatus = $store->status === 'active' ? 'suspended' : 'active';

        DB::transaction(function () use ($store, $newStatus) {
            $store->update(['status' => $newStatus]);

            // Jika toko di-suspend, suspend juga seluruh produk di toko tersebut
            if ($newStatus === 'suspended') {
                $store->products()->update(['status' => 'suspended']);
            } elseif ($newStatus === 'active') {
                $store->products()->update(['status' => 'active']);
            }
        });

        $msg = $newStatus === 'active'
            ? "Toko '{$store->name}' berhasil diaktifkan kembali beserta seluruh produknya."
            : "Toko '{$store->name}' berhasil ditangguhkan/diblokir oleh Admin.";

        return back()->with('success', $msg);
    }

    /**
     * Admin: Hapus Toko beserta produknya.
     */
    public function deleteStore(Store $store)
    {
        $storeName = $store->name;

        DB::transaction(function () use ($store) {
            $store->products()->update(['status' => 'deleted_by_admin']);
            $store->products()->delete();
            $store->update(['status' => 'deleted_by_admin']);
            $store->delete();
        });

        return back()->with('success', "Toko '{$storeName}' beserta seluruh produknya berhasil dihapus oleh Admin.");
    }

    /**
     * Admin: Nonaktifkan atau Aktifkan akun pengguna.
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->isAdmin() && $user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Admin Anda sendiri.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusLabel = $user->is_active ? 'diaktifkan' : 'dinonaktifkan/diblokir';

        return back()->with('success', "Akun pengguna '{$user->name}' berhasil {$statusLabel}.");
    }

    /**
     * Admin: Setujui pendaftaran seller.
     */
    public function approveSeller(User $user)
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['error' => 'Pengguna ini tidak memiliki pendaftaran seller yang menunggu.']);
        }

        $sellerRole = Role::firstWhere('name', 'seller');
        if (!$sellerRole) {
            return back()->withErrors(['error' => 'Role seller tidak ditemukan.']);
        }

        $user->update([
            'role_id'       => $sellerRole->id,
            'seller_status' => 'approved',
        ]);

        return back()->with('success', "Pendaftaran seller '{$user->name}' telah disetujui. Akun telah diupgrade menjadi Seller.");
    }

    /**
     * Admin: Tolak pendaftaran seller.
     */
    public function rejectSeller(User $user)
    {
        if ($user->seller_status !== 'pending') {
            return back()->withErrors(['error' => 'Pengguna ini tidak memiliki pendaftaran seller yang menunggu.']);
        }

        $user->update([
            'seller_status' => 'rejected',
            'ktp_photo'     => null,
            'selfie_photo'  => null,
        ]);

        return back()->with('success', "Pendaftaran seller '{$user->name}' telah ditolak.");
    }

    /**
     * Admin: Copot role Seller dan kembalikan akun pengguna menjadi Buyer.
     */
    public function revokeSeller(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Tidak dapat mencopot role akun Admin.']);
        }

        if (!$user->isSeller()) {
            return back()->withErrors(['error' => 'Pengguna ini bukan seorang Seller.']);
        }

        $buyerRole = Role::firstWhere('name', 'buyer');
        if (!$buyerRole) {
            return back()->withErrors(['error' => 'Role buyer tidak ditemukan di database.']);
        }

        DB::transaction(function () use ($user, $buyerRole) {
            // Ubah role pengguna menjadi buyer dan reset status seller
            $user->update([
                'role_id'       => $buyerRole->id,
                'seller_status' => null,
            ]);

            // Jika pengguna memiliki toko, tangguhkan toko & produknya
            if ($user->store) {
                $user->store->update(['status' => 'suspended']);
            }

            // Tangguhkan semua produk aktif milik pengguna ini
            $user->products()->where('status', 'active')->update(['status' => 'suspended']);
        });

        return back()->with('success', "Role Seller pada pengguna '{$user->name}' berhasil dicopot dan diubah menjadi Buyer. Toko dan produk aktifnya telah ditangguhkan.");
    }

    /**
     * Admin: Ubah peran (Role) pengguna menjadi Admin, Seller, atau Buyer secara fleksibel.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_name' => ['required', 'string', 'in:admin,seller,buyer'],
        ]);

        $role = Role::where('name', $request->role_name)->firstOrFail();

        // Keamanan: Jangan biarkan admin menurunkan role akunnya sendiri (mencegah lockout)
        if ($user->id === auth()->id() && $role->name !== 'admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat menurunkan hak akses Admin pada akun Anda sendiri.']);
        }

        DB::transaction(function () use ($user, $role) {
            $updateData = ['role_id' => $role->id];

            if ($role->name === 'seller') {
                $updateData['seller_status'] = 'approved';
                if ($user->store && $user->store->status === 'suspended') {
                    $user->store->update(['status' => 'active']);
                }
            } elseif ($role->name === 'buyer') {
                $updateData['seller_status'] = null;
                // Jika memiliki toko, tangguhkan toko & produk aktifnya
                if ($user->store) {
                    $user->store->update(['status' => 'suspended']);
                }
                $user->products()->where('status', 'active')->update(['status' => 'suspended']);
            } elseif ($role->name === 'admin') {
                $updateData['seller_status'] = 'approved';
            }

            $user->update($updateData);
        });

        $roleLabels = [
            'admin'  => '🛡️ Admin Platform',
            'seller' => '🏬 Seller Terverifikasi',
            'buyer'  => '👤 Buyer / Member Reguler',
        ];

        $roleLabel = $roleLabels[$role->name] ?? strtoupper($role->name);

        return back()->with('success', "Peran pengguna '{$user->name}' berhasil diubah menjadi {$roleLabel}.");
    }
}

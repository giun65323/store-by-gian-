<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $this->call(RoleSeeder::class);

        $adminRole  = Role::where('name', 'admin')->first();
        $sellerRole = Role::where('name', 'seller')->first();
        $buyerRole  = Role::where('name', 'buyer')->first();

        // 2. Seed Primary User (alvarezi.tino@gmail.com)
        $userAlvar = User::updateOrCreate(
            ['email' => 'alvarezi.tino@gmail.com'],
            [
                'role_id'           => $sellerRole->id,
                'name'              => 'Alvar Gian Aetren',
                'password'          => Hash::make('password123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Seed User (giun65323@gmail.com)
        $userGian = User::updateOrCreate(
            ['email' => 'giun65323@gmail.com'],
            [
                'role_id'           => $sellerRole->id,
                'name'              => 'gian aetren',
                'password'          => Hash::make('password123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Seed Store for Alvar
        $store = Store::updateOrCreate(
            ['seller_id' => $userAlvar->id],
            [
                'name'        => 'Gian Official Store',
                'slug'        => 'gian-official-store-' . $userAlvar->id,
                'description' => 'Toko resmi item & voucher game termurah dan terpercaya.',
                'status'      => 'active',
            ]
        );

        // 5. Seed Products for Alvar
        Product::updateOrCreate(
            ['name' => '1000 Robux Instant Delivery', 'user_id' => $userAlvar->id],
            [
                'store_id'    => $store->id,
                'description' => 'Robux resmi, pengiriman cepat 1-5 menit.',
                'price'       => 150000,
                'stock'       => 50,
                'status'      => 'active',
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Mobile Legends Weekly Diamond Pass', 'user_id' => $userAlvar->id],
            [
                'store_id'    => $store->id,
                'description' => 'Top Up WDP resmi via ID dan Server MLBB.',
                'price'       => 28500,
                'stock'       => 100,
                'status'      => 'active',
            ]
        );

        // 6. Seed Additional Accounts
        User::updateOrCreate(
            ['email' => 'admin@giangames.com'],
            [
                'role_id'           => $adminRole->id,
                'name'              => 'Admin gian games',
                'password'          => Hash::make('admin123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'seller@giangames.com'],
            [
                'role_id'           => $sellerRole->id,
                'name'              => 'Gian Seller',
                'password'          => Hash::make('seller123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $buyer = User::updateOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'role_id'           => $buyerRole->id,
                'name'              => 'Budi Pembeli',
                'password'          => Hash::make('buyer123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // 7. Seed Sample Support Ticket & Message
        $admin = User::where('email', 'admin@giangames.com')->first();

        $ticket = SupportTicket::firstOrCreate(
            [
                'user_id' => $buyer->id,
                'topic'   => 'Konfirmasi Pembelian Robux',
            ],
            [
                'admin_id' => $admin ? $admin->id : null,
                'status'   => 'open',
            ]
        );

        // 8. Seed Sample Order (matching Itemku screenshot)
        $sellerRezzy = User::updateOrCreate(
            ['email' => 'rezzy@store.com'],
            [
                'role_id'           => $sellerRole->id,
                'name'              => 'REZZY STORED',
                'password'          => Hash::make('seller123'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $storeRezzy = Store::updateOrCreate(
            ['seller_id' => $sellerRezzy->id],
            [
                'name'        => 'REZZY STORED',
                'slug'        => 'rezzy-stored',
                'description' => 'Toko resmi VSPhone & Voucher Instant.',
                'status'      => 'active',
            ]
        );

        $productBasic = Product::updateOrCreate(
            ['name' => 'Basic 1 Day', 'user_id' => $sellerRezzy->id],
            [
                'store_id'    => $storeRezzy->id,
                'description' => 'VSPhone Basic 1 Day key & access.',
                'price'       => 2700,
                'stock'       => 99,
                'status'      => 'active',
            ]
        );

        $sampleOrder = \App\Models\Order::updateOrCreate(
            ['order_number' => 'OD000000164288180'],
            [
                'buyer_id'       => $userAlvar->id,
                'seller_id'      => $sellerRezzy->id,
                'store_id'       => $storeRezzy->id,
                'product_id'     => $productBasic->id,
                'product_name'   => 'Basic 1 Day',
                'category_name'  => 'VSPhone',
                'price'          => 2700,
                'quantity'       => 1,
                'total_price'    => 2700,
                'status'         => 'completed',
                'voucher_code'   => 'CAFexmCsaBM',
                'payment_method' => 'DANA',
                'delivery_type'  => 'Pengiriman INSTAN',
            ]
        );

        \App\Models\OrderMessage::firstOrCreate(
            [
                'order_id'  => $sampleOrder->id,
                'sender_id' => $sellerRezzy->id,
            ],
            [
                'receiver_id' => $userAlvar->id,
                'message'     => 'Halo kak! Terima kasih sudah berbelanja di REZZY STORED. Kode voucher sudah aktif dan siap digunakan ya!',
                'is_read'     => false,
            ]
        );
    }
}

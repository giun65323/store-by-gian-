<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Akses penuh untuk moderasi platform, toko, produk, dan menjawab tiket support.',
            ],
            [
                'name'         => 'seller',
                'display_name' => 'Penjual (Seller)',
                'description'  => 'Dapat membuka toko, mengelola inventaris produk jualan, dan membuka tiket bantuan.',
            ],
            [
                'name'         => 'buyer',
                'display_name' => 'Pembeli (Buyer)',
                'description'  => 'Dapat melakukan pembelian produk dan mengajukan tiket chat support ke admin.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}

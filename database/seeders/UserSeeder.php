<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin (Full Akses: Lihat, Tambah, Ubah, Hapus)
        User::updateOrCreate(
            ['email' => 'admin@eventcraft.com'],
            [
                'name' => 'Alwan Rifky Muhana',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Role Ke-2: Staff / Petugas (Akses: Lihat, Tambah, Ubah; Tidak Bisa Hapus)
        User::updateOrCreate(
            ['email' => 'staff@eventcraft.com'],
            [
                'name' => 'Petugas Tiket EventCraft',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );
    }
}

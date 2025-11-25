<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminOwnerSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin - Data minimal
        User::create([
            'name' => 'Admin Pinjaman',
            'username' => 'admin',
            'email' => 'admin@pinjaman.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        // ✅ Owner - Data minimal
        User::create([
            'name' => 'Owner Pinjaman',
            'username' => 'owner',
            'email' => 'owner@pinjaman.com',
            'password' => Hash::make('Owner@123'),
            'role' => 'owner',
        ]);

        // ✅ Sample Customer - Data lengkap
        User::create([
            'name' => 'Customer Demo',
            'username' => 'customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('Customer@123'),
            'role' => 'customer',
            'no_hp' => '081234567890',
            'no_hp2' => '081234567891',
            'nama_no_hp2' => 'Emergency Contact',
            'relasi_no_hp2' => 'Keluarga',
            'NIK' => '1234567890123456',
            'Norek' => '1234567890',
            'Nama_Ibu' => 'Ibu Kandung',
            'Pekerjaan' => 'Karyawan Swasta',
            'Gaji' => '5000000',
            'alamat' => 'Jl. Customer No. 123, Jakarta',
            'kode_bank' => '002',
        ]);

        $this->command->info('✅ Admin, Owner, and Sample Customer created!');
    }
}

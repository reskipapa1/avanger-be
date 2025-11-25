<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'kode_bank' => '002',
                'nama_bank' => 'BRI',
                'alamat' => 'Jl. Jend. Sudirman No. 477',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
            [
                'kode_bank' => '008',
                'nama_bank' => 'Mandiri',
                'alamat' => 'Jl. Jend. Sudirman No. 225',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
            [
                'kode_bank' => '009',
                'nama_bank' => 'BNI',
                'alamat' => 'Jl. Jend. Sudirman No. 406',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
            [
                'kode_bank' => '014',
                'nama_bank' => 'BCA',
                'alamat' => 'Jl. Jend. Sudirman No. 339',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
            [
                'kode_bank' => '013',
                'nama_bank' => 'Permata',
                'alamat' => 'Jl. Jend. Sudirman No. 150',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
            [
                'kode_bank' => '022',
                'nama_bank' => 'CIMB Niaga',
                'alamat' => 'Jl. Jend. Sudirman No. 365',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau'
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }

        $this->command->info('✅ ' . count($banks) . ' banks (Pekanbaru) seeded successfully!');
    }
}

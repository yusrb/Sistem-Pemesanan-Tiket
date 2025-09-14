<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\User;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $jadwal = Jadwal::first();

        Pemesanan::create([
            'user_id' => $user->id,
            'jadwal_id' => $jadwal->id,
            'jumlah_penumpang' => 1,
            'total_harga' => 150000.00,
            'status' => 'paid',
        ]);
    }
}
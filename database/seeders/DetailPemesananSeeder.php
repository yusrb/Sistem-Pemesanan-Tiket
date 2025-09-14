<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPemesanan;
use App\Models\Pemesanan;
use App\Models\Penumpang;
use App\Models\Gerbong;

class DetailPemesananSeeder extends Seeder
{
    public function run(): void
    {
        $pemesanan = Pemesanan::first();
        $penumpang = Penumpang::first();
        $gerbong = Gerbong::first();

        DetailPemesanan::create([
            'pemesanan_id' => $pemesanan->id,
            'penumpang_id' => $penumpang->id,
            'gerbong_id' => $gerbong->id,
            'kode' => 'TKT-001',
            'status' => 'booked',
        ]);
    }
}
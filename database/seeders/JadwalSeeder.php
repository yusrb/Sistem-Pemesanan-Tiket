<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Kereta;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $kereta = Kereta::first();

        Jadwal::create([
            'kereta_id' => $kereta->id,
            'stasiun_awal' => 'Jakarta',
            'stasiun_akhir' => 'Bandung',
            'tanggal' => '2025-09-15',
            'jam_berangkat' => '07:00:00',
            'jam_sampai' => '10:00:00',
            'harga' => 150000.00,
        ]);

        Jadwal::create([
            'kereta_id' => $kereta->id,
            'stasiun_awal' => 'Jakarta',
            'stasiun_akhir' => 'Yogyakarta',
            'tanggal' => '2025-09-16',
            'jam_berangkat' => '08:00:00',
            'jam_sampai' => '14:00:00',
            'harga' => 300000.00,
        ]);

        Jadwal::create([
            'kereta_id' => $kereta->id,
            'stasiun_awal' => 'Bandung',
            'stasiun_akhir' => 'Surabaya',
            'tanggal' => '2025-09-17',
            'jam_berangkat' => '09:00:00',
            'jam_sampai' => '18:00:00',
            'harga' => 450000.00,
        ]);
    }
}
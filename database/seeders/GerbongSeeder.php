<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerbong;
use App\Models\Kereta;

class GerbongSeeder extends Seeder
{
    public function run(): void
    {
        $kereta = Kereta::first();

        Gerbong::create([
            'kode_gerbong' => 'A1',
            'kereta_id' => $kereta->id,
            'jumlah_kursi' => 60,
        ]);
    }
}
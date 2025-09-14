<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penumpang;

class PenumpangSeeder extends Seeder
{
    public function run(): void
    {
        Penumpang::create([
            'nik' => '1234567890123456',
            'nama' => 'John Doe',
        ]);
    }
}
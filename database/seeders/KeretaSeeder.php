<?php

namespace Database\Seeders;

use App\Models\Kereta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KeretaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kereta::create([
            'nama' => 'T.M Amanah O',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'byrn',
            'nik' => '1234567891234567',
            'no_telepon' => '0895323955552',
            'email' => "byrn.uiy@gmail.com",
            'password' => Hash::make('password'),
            'role' => 'penumpang',
        ]);

        User::create([
            'nama' => 'Admin',
            'nik' => '0000000000000000',
            'no_telepon' => null,
            'email' => "admin@gmail.com",
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Petugas Stasiun',
            'nik' => '1111111111111111',
            'no_telepon' => '081111111111',
            'email' => "petugas@gmail.com",
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        User::create([
            'nama' => 'Penumpang II',
            'nik' => '2222222222222222',
            'no_telepon' => '082222222222',
            'email' => "pe1@gmail.com",
            'password' => Hash::make('password'),
            'role' => 'penumpang',
        ]);
    }
}
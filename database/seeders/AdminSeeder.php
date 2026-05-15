<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'a'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'phone'    => '081234567890',
            ]
        );

        // Sample masyarakat user for testing
        User::updateOrCreate(
            ['email' => 'user@pengaduan.desa.id'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('user123'),
                'role'     => 'masyarakat',
                'phone'    => '085678901234',
            ]
        );
    }
}

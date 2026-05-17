<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'system_name', 'value' => 'Pengaduan Desa', 'type' => 'text'],
            ['key' => 'system_sub_name', 'value' => 'Infrastruktur', 'type' => 'text'],
            ['key' => 'footer_text', 'value' => 'Sistem Pelaporan Kerusakan Infrastruktur Desa Terpadu.', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'info@pengaduan.desa.id', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+62 812 3456 7890', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Kantor Kepala Desa, Jl. Merdeka No.1', 'type' => 'text'],
            ['key' => 'logo_url', 'value' => '', 'type' => 'image'], // kosongkan defaultnya, atau kasih gambar default
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}

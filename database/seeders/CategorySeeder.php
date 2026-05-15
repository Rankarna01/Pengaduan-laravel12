<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Jalan',           'icon' => '🛣️'],
            ['name' => 'Jembatan',        'icon' => '🌉'],
            ['name' => 'Irigasi',         'icon' => '💧'],
            ['name' => 'Gedung',          'icon' => '🏛️'],
            ['name' => 'Fasilitas Umum',  'icon' => '🏪'],
            ['name' => 'Drainase',        'icon' => '🚰'],
            ['name' => 'Penerangan',      'icon' => '💡'],
            ['name' => 'Lainnya',         'icon' => '📋'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                ['icon' => $cat['icon']]
            );
        }
    }
}

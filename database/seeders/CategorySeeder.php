<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Jalan',           'icon' => 'fa-solid fa-road'],
            ['name' => 'Jembatan',        'icon' => 'fa-solid fa-bridge'],
            ['name' => 'Irigasi',         'icon' => 'fa-solid fa-water'],
            ['name' => 'Gedung',          'icon' => 'fa-solid fa-building'],
            ['name' => 'Fasilitas Umum',  'icon' => 'fa-solid fa-tree-city'],
            ['name' => 'Drainase',        'icon' => 'fa-solid fa-faucet-drip'],
            ['name' => 'Penerangan',      'icon' => 'fa-solid fa-lightbulb'],
            ['name' => 'Lainnya',         'icon' => 'fa-solid fa-clipboard-list'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                ['icon' => $cat['icon']]
            );
        }
    }
}

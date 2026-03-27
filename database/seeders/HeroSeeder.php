<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hero::insert([
            ['name' => 'Taara', 'image' => 'Taara.png', 'position' => 'Đường Ceasar', 'role' => 'Đấu sĩ & Đỡ đòn'],
            ['name' => 'Raz', 'image' => 'Raz.png', 'position' => 'Mid', 'role' => 'Pháp sư & Sát thủ'],
            ['name' => 'Nakroth', 'image' => 'Nakroth.png', 'position' => 'Jungle','role'=>'Sát thủ'],
            ['name' => 'Valhein', 'image' => 'Valhein.png', 'position' => 'Đường Rồng','role'=>'Xạ thủ'],
            ['name' => 'Annette', 'image' => 'Annette.png', 'position' => 'Đường Rồng','role'=>'Trợ thủ'],
            // Bạn có thể copy thêm hàng loạt tại đây
        ]);
    }
}

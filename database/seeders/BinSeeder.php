<?php

namespace Database\Seeders;

use App\Models\Bin;
use Illuminate\Database\Seeder;

class BinSeeder extends Seeder
{
    public function run(): void
    {
        $bins = [
            ['bin_code' => 'BIN-001', 'location_name' => 'Kampala Road', 'latitude' => 0.3136, 'longitude' => 32.5811, 'fill_level' => 85, 'status' => 'full'],
            ['bin_code' => 'BIN-002', 'location_name' => 'Nakasero Market', 'latitude' => 0.3163, 'longitude' => 32.5761, 'fill_level' => 45, 'status' => 'partial'],
            ['bin_code' => 'BIN-003', 'location_name' => 'Wandegeya', 'latitude' => 0.3350, 'longitude' => 32.5735, 'fill_level' => 92, 'status' => 'full'],
            ['bin_code' => 'BIN-004', 'location_name' => 'Kikuubo', 'latitude' => 0.3145, 'longitude' => 32.5730, 'fill_level' => 20, 'status' => 'empty'],
            ['bin_code' => 'BIN-005', 'location_name' => 'Ntinda', 'latitude' => 0.3545, 'longitude' => 32.6135, 'fill_level' => 78, 'status' => 'partial'],
        ];

        foreach ($bins as $bin) {
            Bin::create($bin);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Truck;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    public function run(): void
    {
        Truck::create([
            'plate_number' => 'UBG 123A',
            'driver_name' => 'Moses Driver',
            'driver_phone' => '0780000001',
            'capacity' => 100,
            'status' => 'available',
        ]);

        Truck::create([
            'plate_number' => 'UBG 456B',
            'driver_name' => 'Peter Driver',
            'driver_phone' => '0780000002',
            'capacity' => 100,
            'status' => 'available',
        ]);
    }
}
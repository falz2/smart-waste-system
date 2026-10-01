<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin KCCA',
            'email' => 'admin@smartwaste.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0700000001',
        ]);

        User::create([
            'name' => 'John Collector',
            'email' => 'collector@smartwaste.com',
            'password' => Hash::make('password'),
            'role' => 'collector',
            'phone' => '0700000002',
        ]);

        User::create([
            'name' => 'Jane Resident',
            'email' => 'resident@smartwaste.com',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'phone' => '0700000003',
            'address' => 'Kampala Central',
        ]);
    }
}
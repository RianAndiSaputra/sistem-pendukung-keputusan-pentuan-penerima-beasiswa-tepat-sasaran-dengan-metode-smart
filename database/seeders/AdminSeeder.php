<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@mercubuana-yogya.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin'
        ]);

        Admin::create([
            'name' => 'Operator Beasiswa',
            'email' => 'operator@mercubuana-yogya.ac.id',
            'password' => Hash::make('operator123'),
            'role' => 'operator'
        ]);
    }
}
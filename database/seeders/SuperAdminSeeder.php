<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'daniappset@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
} 
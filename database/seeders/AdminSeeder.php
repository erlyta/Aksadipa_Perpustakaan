<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'full_name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // ✅ BENAR
            'role' => 'admin',
            'created_at' => now(),
        ]);
    }
}

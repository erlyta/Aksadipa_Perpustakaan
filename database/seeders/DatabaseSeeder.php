<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // jalankan seeder admin
        $this->call([
    AdminSeeder::class,
    CategorySeeder::class,
    BookSeeder::class,
    LoanSeeder::class,
    ]);
    }
}

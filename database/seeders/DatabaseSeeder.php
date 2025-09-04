<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil UserSeeder untuk membuat data admin dan seller
        $this->call(UserSeeder::class);
        
        // Memanggil PenjualSeeder untuk membuat data penjual
        $this->call(PenjualSeeder::class);
    }
}

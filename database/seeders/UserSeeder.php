<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'UserAdmin',
            'email' => 'a@a.a',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        // Seller batch (L001 - L016)
        for ($i = 1; $i <= 16; $i++) {
            User::create([
                'username' => "Seller{$i}",
                'email' => "seller{$i}@mail.com",
                'password' => Hash::make('seller123'),
                'role' => 'seller'
            ]);
        }
    }
}

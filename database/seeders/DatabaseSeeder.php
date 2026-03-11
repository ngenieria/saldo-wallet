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
        $this->call([
            CurrencySeeder::class,
        ]);
        
        // Create an admin user
        \App\Models\Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@saldo.com.co',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin',
        ]);
    }
}

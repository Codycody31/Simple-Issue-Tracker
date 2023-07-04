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
        // \App\Models\User::factory(10)->create();

        // Create a default admin user
        \App\Models\User::factory()->create([
            'fullname' => 'Administrator',
            'email' => 'admin@localhost.com',
            'password' => bcrypt('password'),
        ]);
    }
}

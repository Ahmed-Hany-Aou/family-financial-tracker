<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            PositionsTableSeeder::class,
            FamiliesTableSeeder::class,
            FamilyMembersTableSeeder::class,
            ExchangeRatesSeeder::class,
            AccountsSeeder::class,
        ]);

        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@familytracker.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

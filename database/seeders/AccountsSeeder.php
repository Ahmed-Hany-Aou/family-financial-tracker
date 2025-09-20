<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ahmed Family Accounts
        Account::create([
            'family_member_id' => 1, // Hany Ahmed
            'name' => 'Hany Main Account',
            'type' => 'main',
            'usd_balance' => 5000.00,
            'egp_balance' => 15000.00,
            'is_isolated' => false,
            'description' => 'Primary account for Hany Ahmed',
        ]);

        Account::create([
            'family_member_id' => 1, // Hany Ahmed
            'name' => 'Hany Savings',
            'type' => 'main',
            'usd_balance' => 10000.00,
            'egp_balance' => 0.00,
            'is_isolated' => false,
            'description' => 'Long-term savings account',
        ]);

        Account::create([
            'family_member_id' => 2, // Fatma Ahmed
            'name' => 'Fatma Personal',
            'type' => 'main',
            'usd_balance' => 2000.00,
            'egp_balance' => 8000.00,
            'is_isolated' => false,
            'description' => 'Personal account for Fatma',
        ]);

        Account::create([
            'family_member_id' => 3, // Ahmed Hany
            'name' => 'Ahmed Allowance',
            'type' => 'main',
            'usd_balance' => 200.00,
            'egp_balance' => 1000.00,
            'is_isolated' => false,
            'description' => 'Monthly allowance account',
        ]);

        Account::create([
            'family_member_id' => 4, // Tarek Hany
            'name' => 'Tarek Allowance',
            'type' => 'main',
            'usd_balance' => 150.00,
            'egp_balance' => 800.00,
            'is_isolated' => false,
            'description' => 'Monthly allowance account',
        ]);

        // Smith Family Accounts
        Account::create([
            'family_member_id' => 5, // John Smith
            'name' => 'John Main Account',
            'type' => 'main',
            'usd_balance' => 8000.00,
            'egp_balance' => 0.00,
            'is_isolated' => false,
            'description' => 'Primary account for John Smith',
        ]);

        Account::create([
            'family_member_id' => 5, // John Smith
            'name' => 'John Investment',
            'type' => 'main',
            'usd_balance' => 15000.00,
            'egp_balance' => 0.00,
            'is_isolated' => false,
            'description' => 'Investment portfolio account',
        ]);

        Account::create([
            'family_member_id' => 6, // Sarah Smith
            'name' => 'Sarah Personal',
            'type' => 'main',
            'usd_balance' => 3000.00,
            'egp_balance' => 0.00,
            'is_isolated' => false,
            'description' => 'Personal account for Sarah',
        ]);

        Account::create([
            'family_member_id' => 7, // Emma Smith
            'name' => 'Emma College Fund',
            'type' => 'main',
            'usd_balance' => 500.00,
            'egp_balance' => 0.00,
            'is_isolated' => false,
            'description' => 'College savings account',
        ]);

        // Johnson Family Accounts
        Account::create([
            'family_member_id' => 8, // Michael Johnson
            'name' => 'Michael Business',
            'type' => 'main',
            'usd_balance' => 12000.00,
            'egp_balance' => 25000.00,
            'is_isolated' => false,
            'description' => 'Business account for Michael',
        ]);

        Account::create([
            'family_member_id' => 9, // Lisa Johnson
            'name' => 'Lisa Personal',
            'type' => 'main',
            'usd_balance' => 4000.00,
            'egp_balance' => 12000.00,
            'is_isolated' => false,
            'description' => 'Personal account for Lisa',
        ]);

        // Some isolated accounts for demonstration
        Account::create([
            'family_member_id' => 1, // Hany Ahmed
            'name' => 'Hany Emergency Fund',
            'type' => 'main',
            'usd_balance' => 2000.00,
            'egp_balance' => 5000.00,
            'is_isolated' => true,
            'isolation_until' => now()->addMonths(6),
            'description' => 'Emergency fund - locked for 6 months',
        ]);

        Account::create([
            'family_member_id' => 5, // John Smith
            'name' => 'John Vacation Fund',
            'type' => 'main',
            'usd_balance' => 3000.00,
            'egp_balance' => 0.00,
            'is_isolated' => true,
            'isolation_until' => now()->addMonths(3),
            'description' => 'Vacation savings - locked for 3 months',
        ]);
    }
}

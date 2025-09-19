<?php

namespace Database\Seeders;

use App\Models\FamilyMember;
use App\Models\Account;
use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Single Responsibility: Seeds family data
 * Following your exact family structure and balances
 */
class FamilyMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Create Hany (Father) with multiple accounts
        $hany = FamilyMember::create([
            'name' => 'Hany (Father)',
            'email' => 'hany@family.com',
            'role' => 'father',
            'permission_level' => 'read_write',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);

        // Hany's accounts
        Account::create([
            'family_member_id' => $hany->id,
            'name' => 'House/Life Expenses',
            'type' => 'house',
            'usd_balance' => 143,
            'egp_balance' => 0
        ]);

        Account::create([
            'family_member_id' => $hany->id,
            'name' => 'Apartment Finishing',
            'type' => 'apartment', 
            'usd_balance' => 248,
            'egp_balance' => 0
        ]);

        Account::create([
            'family_member_id' => $hany->id,
            'name' => 'September 2025 - Isolated',
            'type' => 'isolated',
            'usd_balance' => 2500,
            'egp_balance' => 0,
            'is_isolated' => true,
            'isolation_until' => '2025-09-01'
        ]);

        // Create sons
        $sons = [
            ['name' => 'Ahmed', 'email' => 'ahmed@family.com', 'balance' => 3001],
            ['name' => 'Tarek', 'email' => 'tarek@family.com', 'balance' => 97], 
            ['name' => 'Adhm', 'email' => 'adhm@family.com', 'balance' => 150]
        ];

        foreach ($sons as $sonData) {
            $son = FamilyMember::create([
                'name' => $sonData['name'],
                'email' => $sonData['email'],
                'role' => 'son',
                'permission_level' => 'read_only',
                'password' => Hash::make('password'),
                'is_active' => true
            ]);

            Account::create([
                'family_member_id' => $son->id,
                'name' => 'Main Account',
                'type' => 'main',
                'usd_balance' => $sonData['balance'],
                'egp_balance' => 0
            ]);
        }

        // Create current exchange rate
        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'EGP',
            'rate' => 48.32,
            'source' => 'Banque Misr',
            'is_active' => true
        ]);
    }
}
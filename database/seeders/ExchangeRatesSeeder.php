<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'EGP',
            'rate' => 48.32,
            'date' => now(),
        ]);

        ExchangeRate::create([
            'from_currency' => 'EGP',
            'to_currency' => 'USD',
            'rate' => 0.0207,
            'date' => now(),
        ]);

        // Historical rates
        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'EGP',
            'rate' => 47.85,
            'date' => now()->subDays(7),
        ]);

        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'EGP',
            'rate' => 48.10,
            'date' => now()->subDays(3),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Family::create([
            'family_name' => 'Ahmed Family',
        ]);

        Family::create([
            'family_name' => 'Smith Family',
        ]);

        Family::create([
            'family_name' => 'Johnson Family',
        ]);
    }
}

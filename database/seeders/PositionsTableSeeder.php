<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'father', 'description' => 'Head of family'],
            ['name' => 'mother', 'description' => 'Family matriarch'],
            ['name' => 'son', 'description' => 'Male child'],
            ['name' => 'daughter', 'description' => 'Female child'],
            ['name' => 'nephew', 'description' => 'Brother\'s or sister\'s son'],
            ['name' => 'niece', 'description' => 'Brother\'s or sister\'s daughter'],
            ['name' => 'uncle', 'description' => 'Father\'s or mother\'s brother'],
            ['name' => 'aunt', 'description' => 'Father\'s or mother\'s sister'],
            ['name' => 'grandfather', 'description' => 'Father\'s or mother\'s father'],
            ['name' => 'grandmother', 'description' => 'Father\'s or mother\'s mother'],
            ['name' => 'other', 'description' => 'Other family relationship']
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(
                ['name' => $position['name']],
                ['description' => $position['description']]
            );
        }
    }
}

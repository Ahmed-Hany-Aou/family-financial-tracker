<?php

namespace Database\Seeders;

use App\Models\FamilyMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FamilyMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ahmed Family
        FamilyMember::create([
            'family_id' => 1,
            'position_id' => 1, // father
            'name' => 'Hany Ahmed',
            'email' => 'hany@ahmedfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 1,
            'position_id' => 2, // mother
            'name' => 'Fatma Ahmed',
            'email' => 'fatma@ahmedfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 1,
            'position_id' => 3, // son
            'name' => 'Ahmed Hany',
            'email' => 'ahmed@ahmedfamily.com',
            'permission_level' => 'read_only',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 1,
            'position_id' => 3, // son
            'name' => 'Tarek Hany',
            'email' => 'tarek@ahmedfamily.com',
            'permission_level' => 'read_only',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Smith Family
        FamilyMember::create([
            'family_id' => 2,
            'position_id' => 1, // father
            'name' => 'John Smith',
            'email' => 'john@smithfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 2,
            'position_id' => 2, // mother
            'name' => 'Sarah Smith',
            'email' => 'sarah@smithfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 2,
            'position_id' => 4, // daughter
            'name' => 'Emma Smith',
            'email' => 'emma@smithfamily.com',
            'permission_level' => 'read_only',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Johnson Family
        FamilyMember::create([
            'family_id' => 3,
            'position_id' => 1, // father
            'name' => 'Michael Johnson',
            'email' => 'michael@johnsonfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        FamilyMember::create([
            'family_id' => 3,
            'position_id' => 2, // mother
            'name' => 'Lisa Johnson',
            'email' => 'lisa@johnsonfamily.com',
            'permission_level' => 'read_write',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}

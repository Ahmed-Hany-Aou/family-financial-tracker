<?php

namespace Database\Factories;

use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyMemberFactory extends Factory
{
    protected $model = FamilyMember::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'personal_id' => $this->faker->unique()->numerify('##########'),
            'photo' => null,
            'address' => $this->faker->address(),
            'emergency_name' => $this->faker->name(),
            'emergency_phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->sentence(),
            'permission_level' => 'read_only',
            'password' => bcrypt('password'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
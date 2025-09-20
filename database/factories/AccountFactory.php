<?php
namespace Database\Factories;
use App\Models\Account;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->slug(2),
            'family_member_id' => FamilyMember::factory(),
            'usd_balance' => $this->faker->randomFloat(2, 0, 10000),
            'egp_balance' => $this->faker->randomFloat(2, 0, 150000),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
<?php
namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'family_member_id' => FamilyMember::factory(),
            'type' => $this->faker->randomElement(['income', 'expense', 'transfer']),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['USD', 'EGP']),
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
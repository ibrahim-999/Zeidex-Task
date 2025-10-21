<?php

namespace Database\Factories;

use App\Models\Account;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'type' => fake()
                ->randomElement([TransactionType::DEPOSIT, TransactionType::WITHDRAW]),
            'amount' => fake()
                ->randomFloat(2, 100, 10000),
            'created_at' => fake()
                ->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function deposit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::DEPOSIT,
        ]);
    }

    public function withdraw(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::WITHDRAW,
        ]);
    }
}

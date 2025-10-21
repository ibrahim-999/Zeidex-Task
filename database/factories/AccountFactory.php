<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'account_number' => 'ACCT' . fake()
                    ->unique()
                    ->numberBetween(1000, 9999),

            'balance' => fake()
                ->randomFloat(2, 1000, 50000),
        ];
    }
}

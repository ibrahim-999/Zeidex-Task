<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->has(
                Account::factory()
                    ->count(rand(1, 3))
                    ->has(
                        Transaction::factory()
                            ->count(rand(5, 15))
                    )
            )
            ->create();
    }
}

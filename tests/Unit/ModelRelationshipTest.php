<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_accounts()
    {
        $user = User::factory()->create();
        Account::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->accounts);
    }

    public function test_account_belongs_to_user()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $account->user->id);
    }

    public function test_account_has_many_transactions()
    {
        $account = Account::factory()->create();
        Transaction::factory()->count(5)->create(['account_id' => $account->id]);

        $this->assertCount(5, $account->transactions);
    }

    public function test_transaction_belongs_to_account()
    {
        $account = Account::factory()->create();
        $transaction = Transaction::factory()->create(['account_id' => $account->id]);

        $this->assertEquals($account->id, $transaction->account->id);
    }
}

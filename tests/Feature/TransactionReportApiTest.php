<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_successful_response()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 6000,
        ]);

        $response = $this->getJson('/api/v1/transaction-report');

        $response->assertStatus(200);
    }

    public function test_api_accepts_pagination_parameters()
    {
        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create();
            $account = Account::factory()->create(['user_id' => $user->id]);
            Transaction::factory()->deposit()->create([
                'account_id' => $account->id,
                'amount' => 6000,
            ]);
        }

        $response = $this->getJson('/api/v1/transaction-report?per_page=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

}

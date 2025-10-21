<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Services\TransactionReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransactionReportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TransactionReportService();
    }

    public function test_returns_users_with_deposits_greater_than_5000()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 6000,
        ]);

        $results = $this->service->getUserTransactionReport(10);

        $this->assertCount(1, $results);
        $this->assertEquals($user->name, $results[0]->user);
    }

    public function test_filters_users_with_deposits_less_than_5000()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 3000,
        ]);

        $results = $this->service->getUserTransactionReport(10);

        $this->assertCount(0, $results);
    }

    public function test_calculates_correct_aggregations()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 6000,
        ]);

        Transaction::factory()->withdraw()->create([
            'account_id' => $account->id,
            'amount' => 1000,
        ]);

        $results = $this->service->getUserTransactionReport(10);

        $this->assertEquals(6000, $results[0]->total_deposits);
        $this->assertEquals(1000, $results[0]->total_withdrawals);
        $this->assertEquals(5000, $results[0]->net_balance);
    }

    public function test_orders_by_total_deposits_descending()
    {
        $user1 = User::factory()->create();
        $account1 = Account::factory()->create(['user_id' => $user1->id]);
        Transaction::factory()->deposit()->create([
            'account_id' => $account1->id,
            'amount' => 8000,
        ]);

        $user2 = User::factory()->create();
        $account2 = Account::factory()->create(['user_id' => $user2->id]);
        Transaction::factory()->deposit()->create([
            'account_id' => $account2->id,
            'amount' => 10000,
        ]);

        $results = $this->service->getUserTransactionReport(10);

        $this->assertEquals(10000, $results[0]->total_deposits);
        $this->assertEquals(8000, $results[1]->total_deposits);
    }

    public function test_filters_by_date_range()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 6000,
            'created_at' => '2025-10-05',
        ]);

        Transaction::factory()->deposit()->create([
            'account_id' => $account->id,
            'amount' => 2000,
            'created_at' => '2025-11-05',
        ]);

        $results = $this->service->getUserTransactionReport(10, '2025-10-01', '2025-10-31');

        $this->assertEquals(6000, $results[0]->total_deposits);
    }

    public function test_pagination_works()
    {
        for ($i = 0; $i < 15; $i++) {
            $user = User::factory()->create();
            $account = Account::factory()->create(['user_id' => $user->id]);
            Transaction::factory()->deposit()->create([
                'account_id' => $account->id,
                'amount' => 6000 + $i,
            ]);
        }

        $results = $this->service->getUserTransactionReport(5);

        $this->assertCount(5, $results);
        $this->assertEquals(15, $results->total());
    }
}

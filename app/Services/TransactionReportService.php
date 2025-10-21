<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TransactionReportService
{
    public function getUserTransactionReport($perPage = 10, $dateFrom = null, $dateTo = null)
    {
        $cacheKey = 'transaction_report_' . md5($perPage . $dateFrom . $dateTo);

        return Cache::remember($cacheKey, 3600, function () use ($perPage, $dateFrom, $dateTo) {
            $query = User::select(
                'users.name as user',
                DB::raw('COALESCE(SUM(CASE WHEN transactions.type = "deposit" THEN transactions.amount ELSE 0 END), 0) as total_deposits'),
                DB::raw('COALESCE(SUM(CASE WHEN transactions.type = "withdraw" THEN transactions.amount ELSE 0 END), 0) as total_withdrawals'),
                DB::raw('COALESCE(SUM(CASE WHEN transactions.type = "deposit" THEN transactions.amount ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN transactions.type = "withdraw" THEN transactions.amount ELSE 0 END), 0) as net_balance')
            )
                ->join('accounts', 'users.id', '=', 'accounts.user_id')
                ->join('transactions', 'accounts.id', '=', 'transactions.account_id')
                ->groupBy('users.id', 'users.name');

            if ($dateFrom) {
                $query->where('transactions.created_at', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->where('transactions.created_at', '<=', $dateTo);
            }

            return $query->having('total_deposits', '>', 5000)
                ->orderBy('total_deposits', 'desc')
                ->paginate($perPage);
        });
    }
}

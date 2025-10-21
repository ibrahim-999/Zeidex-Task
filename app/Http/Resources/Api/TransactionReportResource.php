<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->user,
            'total_deposits' => number_format($this->total_deposits, 2),
            'total_withdrawals' => number_format($this->total_withdrawals, 2),
            'net_balance' => number_format($this->net_balance, 2),
        ];
    }
}

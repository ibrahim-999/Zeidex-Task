<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\TransactionReportRequest;
use App\Http\Resources\Api\TransactionReportCollection;
use App\Services\TransactionReportService;

class TransactionReportApiController
{
    public function __construct(
        private TransactionReportService $reportService
    ) {}

    public function index(TransactionReportRequest $request)
    {
        return new TransactionReportCollection(
            $this->reportService
                ->getUserTransactionReport($request->per_page ??
                    10, $request->date_from, $request->date_to)
        );
    }
}

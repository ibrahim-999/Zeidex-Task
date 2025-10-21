<?php

use App\Http\Controllers\Api\v1\TransactionReportApiController;
use App\Services\AggregationService;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::get('/transaction-report', [TransactionReportApiController::class, 'index'])
        ->name('transaction-report');

    Route::get('/aggregation', function (AggregationService $service) {
        return response()->json($service->getAggregatedResults());
    });
});

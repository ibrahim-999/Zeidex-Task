<?php

use App\Http\Controllers\Api\v1\TransactionReportApiController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::get('/transaction-report', [TransactionReportApiController::class, 'index'])
        ->name('transaction-report');
});

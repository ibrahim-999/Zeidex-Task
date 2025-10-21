<?php

use App\Services\AggregationService;
use App\Services\PrimeService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-prime', function () {
    $service = new PrimeService();
    $service->generatePrimes(100);
    return $service->getResults(100);
});


Route::get('/aggregation', function (AggregationService $service) {
    return response()->json($service->getAggregatedResults());
});

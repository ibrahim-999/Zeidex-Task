<?php

namespace Tests\Unit;

use App\Services\PrimeService;
use PHPUnit\Framework\TestCase;

class PrimeServiceTest extends TestCase
{
    private PrimeService $primeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->primeService = new PrimeService();
    }

    public function test_generates_correct_prime_count_for_any_given_number()
    {
        $this->primeService->generatePrimes(1000);
        $this->assertEquals(168, $this->primeService->getPrimeCount());
    }

    public function test_calculates_average_gap()
    {
        $this->primeService->generatePrimes(100);
        $avgGap = $this->primeService->getAverageGap();
        $this->assertGreaterThan(0, $avgGap);
        $this->assertIsFloat($avgGap);
    }

    public function test_calculates_max_gap()
    {
        $this->primeService->generatePrimes(100);
        $maxGap = $this->primeService->getMaxGap();
        $this->assertGreaterThan(0, $maxGap);
        $this->assertIsInt($maxGap);
    }

    public function test_tracks_execution_time()
    {
        $this->primeService->generatePrimes(10000);
        $executionTime = $this->primeService->getExecutionTime();
        $this->assertGreaterThanOrEqual(0, $executionTime);
    }

    public function test_tracks_memory_usage()
    {
        $this->primeService->generatePrimes(1000);
        $memoryUsage = $this->primeService->getMemoryUsage();
        $this->assertGreaterThanOrEqual(0, $memoryUsage);
    }

    public function test_returns_correct_complexity()
    {
        $this->primeService->generatePrimes(100);
        $this->assertEquals('O(n log log n)', $this->primeService->getComplexity());
    }
}

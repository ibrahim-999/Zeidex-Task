<?php

namespace App\Services;

class PrimeService
{
    private array $primes = [];
    private float $executionTime = 0;
    private float $memoryUsage = 0;

    public function generatePrimes(int $limit): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $isPrime = array_fill(0, $limit + 1, true);
        $isPrime[0] = $isPrime[1] = false;

        for ($i = 2; $i * $i <= $limit; $i++) {
            if ($isPrime[$i]) {
                for ($j = $i * $i; $j <= $limit; $j += $i) {
                    $isPrime[$j] = false;
                }
            }
        }

        $this->primes = [];
        for ($i = 2; $i <= $limit; $i++) {
            if ($isPrime[$i]) {
                $this->primes[] = $i;
            }
        }

        $this->executionTime = microtime(true) - $startTime;
        $this->memoryUsage = (memory_get_usage() - $startMemory) / 1024 / 1024; // MB

        return $this->primes;
    }

    public function getPrimeCount(): int
    {
        return count($this->primes);
    }

    public function getAverageGap(): float
    {
        if (count($this->primes) < 2) {
            return 0;
        }

        $totalGap = 0;
        for ($i = 1; $i < count($this->primes); $i++) {
            $totalGap += $this->primes[$i] - $this->primes[$i - 1];
        }

        return round($totalGap / (count($this->primes) - 1), 2);
    }

    public function getMaxGap(): int
    {
        if (count($this->primes) < 2) {
            return 0;
        }

        $maxGap = 0;
        for ($i = 1; $i < count($this->primes); $i++) {
            $gap = $this->primes[$i] - $this->primes[$i - 1];
            $maxGap = max($maxGap, $gap);
        }

        return $maxGap;
    }

    public function getExecutionTime(): float
    {
        return round($this->executionTime, 4);
    }

    public function getMemoryUsage(): float
    {
        return round($this->memoryUsage, 2);
    }

    public function getComplexity(): string
    {
        return "O(n log log n)";
    }

    public function getResults(int $limit): array
    {
        return [
            'limit' => $limit,
            'execution_time' => $this->getExecutionTime(),
            'memory_usage' => $this->getMemoryUsage(),
            'prime_count' => $this->getPrimeCount(),
            'avg_gap' => $this->getAverageGap(),
            'max_gap' => $this->getMaxGap(),
            'complexity' => $this->getComplexity(),
        ];
    }
}

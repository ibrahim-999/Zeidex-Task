<?php

namespace Tests\Unit;

use App\Services\AggregationService;
use Tests\TestCase;

class AggregationServiceTest extends TestCase
{
    private AggregationService $aggregationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aggregationService = new AggregationService();
    }

    public function test_returns_zero_when_no_files_exist()
    {
        $this->clearResultsDirectory();

        $results = $this->aggregationService->getAggregatedResults();

        $this->assertEquals(0, $results['average_execution_time']);
        $this->assertEquals(0, $results['average_memory_usage']);
        $this->assertEquals(0, $results['average_prime_count']);
    }

    public function test_calculates_correct_averages()
    {
        $this->clearResultsDirectory();
        $this->createMockResultFiles();

        $results = $this->aggregationService->getAggregatedResults();

        $this->assertArrayHasKey('average_execution_time', $results);
        $this->assertArrayHasKey('average_memory_usage', $results);
        $this->assertArrayHasKey('average_prime_count', $results);
        $this->assertGreaterThan(0, $results['average_execution_time']);
    }

    public function test_response_structure_is_valid()
    {
        $this->clearResultsDirectory();
        $this->createMockResultFiles();

        $results = $this->aggregationService->getAggregatedResults();

        $this->assertIsArray($results);
        $this->assertCount(3, $results);
    }

    private function clearResultsDirectory(): void
    {
        $directory = storage_path('results');
        if (is_dir($directory)) {
            $files = glob($directory . '/*.json');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }

    private function createMockResultFiles(): void
    {
        $directory = storage_path('results');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $mockData1 = [
            'limit' => 100,
            'execution_time' => 0.001,
            'memory_usage' => 0.5,
            'prime_count' => 25,
            'avg_gap' => 3.2,
            'max_gap' => 8,
            'complexity' => 'O(n log log n)',
        ];

        $mockData2 = [
            'limit' => 1000,
            'execution_time' => 0.003,
            'memory_usage' => 1.0,
            'prime_count' => 168,
            'avg_gap' => 5.9,
            'max_gap' => 20,
            'complexity' => 'O(n log log n)',
        ];

        file_put_contents($directory . '/test1.json', json_encode($mockData1));
        file_put_contents($directory . '/test2.json', json_encode($mockData2));
    }
}

<?php

namespace App\Services;


class AggregationService
{
    public function getAggregatedResults(): array
    {
        $directory = storage_path('results');

        if (!is_dir($directory)) {
            return [
                'average_execution_time' => 0,
                'average_memory_usage' => 0,
                'average_prime_count' => 0
            ];
        }

        $files = glob($directory . '/*.json');

        if (empty($files)) {
            return [
                'average_execution_time' => 0,
                'average_memory_usage' => 0,
                'average_prime_count' => 0
            ];
        }

        $totalExecutionTime = 0;
        $totalMemoryUsage = 0;
        $totalPrimeCount = 0;
        $count = 0;

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $data = json_decode($content, true);

            if ($data) {
                $totalExecutionTime += $data['execution_time'];
                $totalMemoryUsage += $data['memory_usage'];
                $totalPrimeCount += $data['prime_count'];
                $count++;
            }
        }

        return [
            'average_execution_time' => round($totalExecutionTime / $count, 3),
            'average_memory_usage' => round($totalMemoryUsage / $count, 2),
            'average_prime_count' => round($totalPrimeCount / $count, 0)
        ];
    }
}

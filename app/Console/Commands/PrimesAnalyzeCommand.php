<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PrimeService;

class PrimesAnalyzeCommand extends Command
{
    protected $signature = 'primes:analyze {limit}';
    protected $description = 'Analyze prime numbers up to a given limit';

    public function handle(PrimeService $primeService): int
    {
        $limit = (int) $this->argument('limit');

        if ($limit < 2) {
            $this->error('Limit must be at least 2');
            return Command::FAILURE;
        }

        $this->info("Analyzing primes up to {$limit}");

        $primeService->generatePrimes($limit);
        $results = $primeService->getResults($limit);

        $this->newLine();
        $this->line("Total Primes: {$results['prime_count']}");
        $this->line("Average Gap: {$results['avg_gap']}");
        $this->line("Max Gap: {$results['max_gap']}");
        $this->line("Execution Time: {$results['execution_time']}s");
        $this->line("Memory Usage: {$results['memory_usage']}MB");
        $this->line("Estimated Time Complexity: {$results['complexity']}");

        $filename = time() . '_' . $limit . '.json';
        $path = storage_path('results/' . $filename);
        file_put_contents($path, json_encode($results, JSON_PRETTY_PRINT));

        $this->newLine();
        $this->info("Results saved to {$filename}");

        return Command::SUCCESS;
    }
}

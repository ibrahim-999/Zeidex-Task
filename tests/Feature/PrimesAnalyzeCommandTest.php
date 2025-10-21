<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrimesAnalyzeCommandTest extends TestCase
{
    public function test_command_runs_successfully()
    {
        $this->artisan('primes:analyze 100')
            ->expectsOutput('Analyzing primes up to 100')
            ->assertExitCode(0);
    }

    public function test_command_fails_with_invalid_limit()
    {
        $this->artisan('primes:analyze 1')
            ->expectsOutput('Limit must be at least 2')
            ->assertExitCode(1);
    }

    public function test_command_creates_json_file()
    {
        $directory = storage_path('results');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $timestamp = time();

        $this->artisan('primes:analyze 100');

        sleep(1);

        $files = glob($directory . '/*.json');
        $foundNewFile = false;

        foreach ($files as $file) {
            $fileTime = filemtime($file);
            if ($fileTime >= $timestamp) {
                $foundNewFile = true;
                break;
            }
        }

        $this->assertTrue($foundNewFile);
    }
}

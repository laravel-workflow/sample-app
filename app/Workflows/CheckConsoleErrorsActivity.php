<?php

namespace App\Workflows;

use Workflow\Activity;
use Illuminate\Support\Facades\Log;

class CheckConsoleErrorsActivity extends Activity
{
    public function execute(string $url)
    {
        $errors = [];

        $process = new \Symfony\Component\Process\Process([
            'node',
            base_path('playwright-script.js'),
            $url
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Playwright script failed: ' . $process->getErrorOutput());
        }

        $output = $process->getOutput();
        $errors = json_decode($output, true);

        Log::info("Console errors for {$url}:", [$errors]);

        return $errors;
    }
}

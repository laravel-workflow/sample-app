<?php

namespace App\Workflows\Playwright;

use Workflow\Activity;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckConsoleErrorsActivity extends Activity
{
    public function execute(string $url)
    {
        $process = new Process(['node', base_path('playwright-script.js'), $url]);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return json_decode($process->getOutput(), true);
    }
}

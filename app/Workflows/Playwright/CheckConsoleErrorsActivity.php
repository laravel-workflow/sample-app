<?php

namespace App\Workflows\Playwright;

use Illuminate\Support\Facades\Process;
use Workflow\Activity;

class CheckConsoleErrorsActivity extends Activity
{
    public function execute(string $url)
    {
        $result = Process::run([
            'node', base_path('playwright-script.js'), $url
        ])->throw();

        return json_decode($result->output(), true);
    }
}

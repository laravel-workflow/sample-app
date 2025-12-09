<?php

namespace App\Workflows\Playwright;

use Workflow\Workflow;
use function Workflow\activity;

/**
 * See: https://laravel-workflow.com/blog/automating-qa-with-playwright-and-laravel-workflow
 */

class CheckConsoleErrorsWorkflow extends Workflow
{
    public function execute(string $url)
    {
        $result = yield activity(CheckConsoleErrorsActivity::class, $url);

        $mp4 = yield activity(ConvertVideoActivity::class, $result['video']);

        return [
            'errors' => $result['errors'],
            'mp4' => $mp4,
        ];
    }
}

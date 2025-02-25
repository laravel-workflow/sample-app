<?php

namespace App\Workflows\Playwright;

use Workflow\ActivityStub;
use Workflow\Workflow;

/**
 * See: https://laravel-workflow.com/blog/automating-qa-with-playwright-and-laravel-workflow
 */

class CheckConsoleErrorsWorkflow extends Workflow
{
    public function execute(string $url)
    {
        $result = yield ActivityStub::make(CheckConsoleErrorsActivity::class, $url);

        $mp4 = yield ActivityStub::make(ConvertVideoActivity::class, $result['video']);

        return [
            'errors' => $result['errors'],
            'mp4' => $mp4,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Workflows\Webhooks;

use Workflow\Activity;

class WebhookActivity extends Activity
{
    public function execute($message)
    {
        return 'Hello ' . $message;
    }
}

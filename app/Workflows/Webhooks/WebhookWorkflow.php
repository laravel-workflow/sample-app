<?php

declare(strict_types=1);

namespace App\Workflows\Webhooks;

use Workflow\SignalMethod;
use Workflow\Webhook;
use Workflow\Workflow;
use function Workflow\{activity, await};

/**
 * See: https://laravel-workflow.com/docs/features/webhooks
 */

#[Webhook]
class WebhookWorkflow extends Workflow
{
    public bool $ready = false;

    #[SignalMethod]
    #[Webhook]
    public function ready()
    {
        $this->ready = true;
    }

    public function execute($message)
    {
        yield await(fn () => $this->ready);

        $result = yield activity(WebhookActivity::class, $message);

        return $result;
    }
}

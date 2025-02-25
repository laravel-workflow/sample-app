<?php

declare(strict_types=1);

namespace App\Workflows\Webhooks;

use Workflow\ActivityStub;
use Workflow\SignalMethod;
use Workflow\Webhook;
use Workflow\Workflow;
use Workflow\WorkflowStub;

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
        WorkflowStub::await(fn () => $this->ready);

        $result = yield ActivityStub::make(WebhookActivity::class, $message);

        return $result;
    }
}

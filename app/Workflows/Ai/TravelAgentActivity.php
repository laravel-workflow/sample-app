<?php

namespace App\Workflows\Ai;

use App\Ai\Agents\TravelAgent;
use Workflow\Activity;

class TravelAgentActivity extends Activity
{
    public function execute($workflowId, $messages)
    {
        $history = array_slice($messages, 0, -1);
        $currentUserMessage = end($messages);

        error_log('TravelAgentActivity received message: ' . $currentUserMessage->content);

        $response = (new TravelAgent($workflowId))
            ->continue($history)
            ->prompt($currentUserMessage->content);

        error_log('TravelAgentActivity generated response: ' . $response);

        return (string) $response;
    }
}

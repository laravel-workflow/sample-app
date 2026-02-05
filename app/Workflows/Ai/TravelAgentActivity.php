<?php

namespace App\Workflows\Ai;

use App\Ai\Agents\TravelAgent;
use Laravel\Ai\Messages\AssistantMessage;
use Workflow\Activity;

class TravelAgentActivity extends Activity
{
    public function execute($messages)
    {
        $history = array_slice($messages, 0, -1);
        $currentUserMessage = end($messages);

        error_log('TravelAgentActivity received message: ' . $currentUserMessage->content);
        
        $response = (new TravelAgent)
            ->continue($history)
            ->prompt($currentUserMessage->content);

        error_log('TravelAgentActivity generated response: ' . $response);

        return new AssistantMessage($response);
    }
}

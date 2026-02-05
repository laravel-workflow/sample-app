<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class TravelAgent implements Agent, Conversational, HasTools, HasStructuredOutput
{
    use Promptable;

    private array $messages = [];

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a travel agent.';
    }

    /**
     * Continue an existing conversation with the given messages.
     */
    public function continue($messages): static
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     */
    public function messages(): iterable
    {
        return $this->messages;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'text' => $schema->string()->required(),
            'tool' => $schema->string()->required(),
        ];
    }
}

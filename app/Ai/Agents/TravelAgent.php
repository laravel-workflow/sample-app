<?php

namespace App\Ai\Agents;

use App\Ai\Tools\BookFlight;
use App\Ai\Tools\BookHotel;
use App\Ai\Tools\BookRentalCar;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class TravelAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    private array $messages = [];

    public function __construct(
        private readonly int $workflowId,
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
        You are a travel agent. Help users plan and book travel.

        When a user asks you to book a hotel, flight, or rental car, ALWAYS
        call the appropriate booking tool immediately with whatever details
        they provided. Never ask for more details before calling the tool.
        Use reasonable defaults for any missing information.
        INSTRUCTIONS;
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
        return [
            new BookHotel($this->workflowId),
            new BookFlight($this->workflowId),
            new BookRentalCar($this->workflowId),
        ];
    }
}

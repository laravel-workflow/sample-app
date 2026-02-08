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

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
        You are a professional travel agent. Help users plan and book travel.

        BOOKING RULES:
        - When a user asks to book a hotel, flight, or rental car, ALWAYS call
          the appropriate booking tool immediately with whatever details they
          provided. Never ask for more details before calling the tool.
        - Use reasonable defaults for any missing information (e.g. 1 guest,
          next-day dates, economy class).
        - You may call multiple booking tools in a single response if the user
          requests multiple bookings.
        - For flights, always include a return date if the user mentions round
          trip, return dates, or trip end dates. Omit return_date only for
          explicitly one-way flights.

        CONVERSATION RULES:
        - Be concise and action-oriented.
        - After placing bookings, briefly confirm what was booked.
        - You can also help with itinerary planning, destination advice,
          packing lists, and general travel logistics.
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
            new BookHotel(),
            new BookFlight(),
            new BookRentalCar(),
        ];
    }
}

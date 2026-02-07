<?php

namespace App\Workflows\Ai;

use Laravel\Ai\Messages\UserMessage;
use Workflow\SignalMethod;
use Workflow\UpdateMethod;
use Workflow\Workflow;
use function Workflow\{activity, await};

class AiWorkflow extends Workflow
{
    private int $flightId;
    private int $hotelId;
    private int $rentalCarId;

    #[SignalMethod]
    public function send(string $message): void
    {
        $this->inbox->receive($message);
    }

    #[UpdateMethod]
    public function receive()
    {
        return $this->outbox->nextUnsent();
    }

    public function execute()
    {
        $messages = [];
        $done = false;

        try {
            do {
                yield await(fn() => $this->inbox->hasUnread());
    
                $userMessage = new UserMessage($this->inbox->nextUnread());
                $messages[] = $userMessage;
    
                $assistantMessage = yield activity(TravelAgentActivity::class, $messages);
                $messages[] = $assistantMessage;

                $output = json_decode($assistantMessage->content, true);

                switch ($output['tool']) {
                    case 'book_flight':
                        $this->flightId = yield activity(BookFlightActivity::class);
                        $this->addCompensation(fn () => activity(CancelFlightActivity::class, $this->flightId));
                        break;
                    case 'book_hotel':
                        $this->hotelId = yield activity(BookHotelActivity::class);
                        $this->addCompensation(fn () => activity(CancelHotelActivity::class, $this->hotelId));
                        break;
                    case 'book_rental_car':
                        $this->rentalCarId = yield activity(BookRentalCarActivity::class);
                        $this->addCompensation(fn () => activity(CancelRentalCarActivity::class, $this->rentalCarId));
                        break;
                    case 'done':
                        $done = true;
                        break;
                }

                $this->outbox->send($output['text']);

            } while (!$done && count($messages) < 10);

        } catch (Throwable $th) {
            yield from $this->compensate();
            throw $th;
        }


        return $messages;
    }
}

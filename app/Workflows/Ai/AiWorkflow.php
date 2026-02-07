<?php

namespace App\Workflows\Ai;

use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Throwable;
use Workflow\SignalMethod;
use Workflow\UpdateMethod;
use Workflow\Workflow;
use function Workflow\{activity, await};

class AiWorkflow extends Workflow
{
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

    public function execute($injectFailure = null)
    {
        $messages = [];
        $booked = [];

        try {
            do {
                yield await(fn () => $this->inbox->hasUnread());

                $raw = $this->inbox->nextUnread();
                $data = json_decode($raw, true);

                if (is_array($data) && isset($data['type'])) {
                    if ($data['type'] === 'book_hotel') {
                        $result = yield activity(BookHotelActivity::class, $data['hotel_name'], $data['check_in_date'], $data['check_out_date'], (int) $data['guests'], $injectFailure === 'hotel');
                        $this->addCompensation(fn () => activity(CancelHotelBookingActivity::class, $result));
                        $booked[] = 'hotel';
                    } elseif ($data['type'] === 'book_flight') {
                        $result = yield activity(BookFlightActivity::class, $data['origin'], $data['destination'], $data['departure_date'], $injectFailure === 'flight');
                        $this->addCompensation(fn () => activity(CancelFlightBookingActivity::class, $result));
                        $booked[] = 'flight';
                    } elseif ($data['type'] === 'book_rental_car') {
                        $result = yield activity(BookRentalCarActivity::class, $data['pickup_location'], $data['pickup_date'], $data['return_date'], $injectFailure === 'car');
                        $this->addCompensation(fn () => activity(CancelRentalCarBookingActivity::class, $result));
                        $booked[] = 'car';
                    }

                    $messages[] = new AssistantMessage($result);
                    $this->outbox->send($result);
                } else {
                    $userMessage = new UserMessage($raw);
                    $messages[] = $userMessage;

                    $result = yield activity(TravelAgentActivity::class, $this->storedWorkflow->id, $messages);
                    $assistantMessage = new AssistantMessage($result);
                    $messages[] = $assistantMessage;
                    $this->outbox->send($result);
                }

            } while (count($booked) < 3);

        } catch (Throwable $th) {
            yield from $this->compensate();
            throw $th;
        }

        return $messages;
    }
}

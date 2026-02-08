<?php

namespace App\Workflows\Ai;

use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Throwable;
use Workflow\SignalMethod;
use Workflow\UpdateMethod;
use Workflow\Workflow;
use function Workflow\{activity, awaitWithTimeout};

class AiWorkflow extends Workflow
{
    private const MAX_MESSAGES = 20;
    private const INACTIVITY_TIMEOUT = 300; // 5 minutes

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

        try {
            while (count($messages) < self::MAX_MESSAGES) {
                $receivedMessage = yield awaitWithTimeout(
                    self::INACTIVITY_TIMEOUT,
                    fn () => $this->inbox->hasUnread(),
                );

                if (! $receivedMessage) {
                    $this->outbox->send('Session ended due to inactivity. Please start a new conversation.');
                    break;
                }

                $raw = $this->inbox->nextUnread();
                $userMessage = new UserMessage($raw);
                $messages[] = $userMessage;

                $result = yield activity(TravelAgentActivity::class, $messages);
                $data = json_decode($result, true);

                foreach ($data['bookings'] as $booking) {
                    yield from $this->handleBooking($booking, $injectFailure);
                }

                $assistantMessage = new AssistantMessage($data['text']);
                $messages[] = $assistantMessage;
                $this->outbox->send($data['text']);
            }

            if (count($messages) >= self::MAX_MESSAGES) {
                $this->outbox->send('This conversation has reached its message limit. Please start a new conversation to continue.');
            }

        } catch (Throwable $th) {
            yield from $this->compensate();
            $this->outbox->send('Booking failed: ' . $th->getMessage() . '. Any previous bookings have been cancelled.');
        }

        return $messages;
    }

    private function handleBooking(array $data, ?string $injectFailure): \Generator
    {
        return match ($data['type']) {
            'book_hotel' => $this->bookHotel($data, $injectFailure),
            'book_flight' => $this->bookFlight($data, $injectFailure),
            'book_rental_car' => $this->bookRentalCar($data, $injectFailure),
        };
    }

    private function bookHotel(array $data, ?string $injectFailure): \Generator
    {
        $result = yield activity(
            BookHotelActivity::class,
            $data['hotel_name'],
            $data['check_in_date'],
            $data['check_out_date'],
            (int) $data['guests'],
            $injectFailure === 'hotel',
        );
        $this->addCompensation(fn () => activity(CancelHotelActivity::class, $result));

        return $result;
    }

    private function bookFlight(array $data, ?string $injectFailure): \Generator
    {
        $result = yield activity(
            BookFlightActivity::class,
            $data['origin'],
            $data['destination'],
            $data['departure_date'],
            $data['return_date'] ?? null,
            $injectFailure === 'flight',
        );
        $this->addCompensation(fn () => activity(CancelFlightActivity::class, $result));

        return $result;
    }

    private function bookRentalCar(array $data, ?string $injectFailure): \Generator
    {
        $result = yield activity(
            BookRentalCarActivity::class,
            $data['pickup_location'],
            $data['pickup_date'],
            $data['return_date'],
            $injectFailure === 'car',
        );
        $this->addCompensation(fn () => activity(CancelRentalCarActivity::class, $result));

        return $result;
    }
}

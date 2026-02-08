<?php

namespace App\Workflows\Ai;

use Exception;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Throwable;
use Workflow\SignalMethod;
use Workflow\UpdateMethod;
use Workflow\Workflow;
use function Workflow\{activity, awaitWithTimeout};

class AiWorkflow extends Workflow
{
    private const INACTIVITY_TIMEOUT = '2 minutes';

    private const MAX_MESSAGES = 20;

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
                    throw new Exception('Session ended due to inactivity. Please start a new conversation.');
                }

                $messages[] = new UserMessage($this->inbox->nextUnread());
                $result = yield activity(TravelAgentActivity::class, $messages);
                $data = json_decode($result, true);

                foreach ($data['bookings'] as $booking) {
                    yield from $this->handleBooking($booking, $injectFailure);
                }

                $messages[] = new AssistantMessage($data['text']);
                $this->outbox->send($data['text']);
            }

            if (count($messages) >= self::MAX_MESSAGES) {
                throw new Exception('This conversation has reached its message limit. Please start a new conversation to continue.');
            }

        } catch (Throwable $th) {
            yield from $this->compensate();
            $this->outbox->send($th->getMessage() . ' Any previous bookings have been cancelled.');
        }

        return $messages;
    }

    private function handleBooking(array $data, ?string $injectFailure)
    {
        return match ($data['type']) {
            'book_hotel' => $this->bookHotel($data, $injectFailure),
            'book_flight' => $this->bookFlight($data, $injectFailure),
            'book_rental_car' => $this->bookRentalCar($data, $injectFailure),
        };
    }

    private function bookHotel(array $data, ?string $injectFailure)
    {
        $hotel = yield activity(
            BookHotelActivity::class,
            $data['hotel_name'],
            $data['check_in_date'],
            $data['check_out_date'],
            (int) $data['guests'],
            $injectFailure === 'hotel',
        );
        $this->addCompensation(fn () => activity(CancelHotelActivity::class, $hotel));

        return $hotel;
    }

    private function bookFlight(array $data, ?string $injectFailure)
    {
        $flight = yield activity(
            BookFlightActivity::class,
            $data['origin'],
            $data['destination'],
            $data['departure_date'],
            $data['return_date'] ?? null,
            $injectFailure === 'flight',
        );
        $this->addCompensation(fn () => activity(CancelFlightActivity::class, $flight));

        return $flight;
    }

    private function bookRentalCar(array $data, ?string $injectFailure)
    {
        $rentalCar = yield activity(
            BookRentalCarActivity::class,
            $data['pickup_location'],
            $data['pickup_date'],
            $data['return_date'],
            $injectFailure === 'car',
        );
        $this->addCompensation(fn () => activity(CancelRentalCarActivity::class, $rentalCar));

        return $rentalCar;
    }
}

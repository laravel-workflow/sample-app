<?php

namespace App\Workflows\Ai;

use Workflow\Activity;
use Workflow\Exceptions\NonRetryableException;

class BookFlightActivity extends Activity
{
    public function execute(string $origin, string $destination, string $departureDate, ?string $returnDate = null, bool $shouldFail = false)
    {
        if ($shouldFail) {
            throw new NonRetryableException("Flight booking failed: {$origin} to {$destination}");
        }

        $id = random_int(100000, 999999);

        $summary = "Flight booked: {$origin} to {$destination}, departing {$departureDate}";
        if ($returnDate) {
            $summary .= ", returning {$returnDate}";
        } else {
            $summary .= ' (one-way)';
        }
        $summary .= ". Confirmation #{$id}";

        error_log($summary);

        return $summary;
    }
}

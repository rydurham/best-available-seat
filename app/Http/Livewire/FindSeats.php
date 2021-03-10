<?php

namespace App\Http\Livewire;

use App\Actions\FindBestAvailableSeats;
use App\Models\Venue;
use Livewire\Component;
use Illuminate\Support\Arr;
use App\Actions\ReceiveSeats;

class FindSeats extends Component
{
    /**
     * The JSON payload to be processed.
     *
     * @var string
     */
    public $payload;

    /**
     * The result of our seat analysis, formatted as JSON.
     *
     * @var string
     */
    public $determination;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'payload' => 'required'
    ];

    /**
     * Render the page.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.find-seats');
    }

    /**
     * Find the best available seats given the payload definition.
     *
     * @return void
     */
    public function find()
    {
        $this->validate();

        $decoded = json_decode($this->payload, $assoc = true);
        foreach (['venue', 'seats', 'requested_seat_count'] as $key) {
            if (! Arr::has($decoded, $key)) {
                $this->addError('payload', "The '{$key}' key is missing from the payload, or the JSON is malformed.");
            }
        }

        $rows = Arr::get($decoded, 'venue.layout.rows');
        if (!$rows) {
            $this->addError('payload', 'Could not determine the number of rows in the venue.');
        }

        if ($rows > 702) {
            $this->addError('payload', 'You may not specify more than 702 rows.');
        }

        $columns = Arr::get($decoded, 'venue.layout.columns');
        if (!$columns) {
            $this->addError('payload', 'Could not determine the number of columns in the venue.');
        }

        // If payload validation failed we will return early.
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        // Initiate our Venue and Seats
        $preparation = ReceiveSeats::execute([
            'seats' => Arr::get($decoded, 'seats', []),
            'venue' => new Venue($rows, $columns),
        ]);

        if ($preparation->failed()) {
            $this->determination = json_encode([
                'seats' => [],
                'error' => 'There was a problem parsing the venue',
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Attempt to find the best available seats.
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => $decoded['requested_seat_count'],
            'venue' => $preparation->venue,
        ]);

        if ($action->failed()) {
            $this->determination = json_encode([
                'seats' => [],
                'error' => $action->getMessage(),
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Format our result as JSON.
        $this->determination = json_encode([
            'seats' => $action->determination,
            'error' => '',
        ], JSON_PRETTY_PRINT);

        return;
    }
}

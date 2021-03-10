<?php

namespace App\Actions;

use App\Models\Seat;
use Illuminate\Support\Arr;
use StageRightLabs\Actions\Action;

/**
 * Receive a set of seats from a decoded JSON object and add them to a venue.
 *
 * Required:
 *  - venue (\App\Models\Venue)
 *  - seats (array)
 */
class ReceiveSeats extends Action
{
    /**
     * The venue that needs seats.
     *
     * @var Venue
     */
    public $venue;

    /**
     * Handle the action.
     *
     * @param Action|array $input
     * @return self
     */
    public function handle($input = [])
    {
        $this->venue = $input['venue'];

        $this->venue->seats =
            collect($input['seats'])
            ->reduce(function ($carry, $seat) {
                if (Arr::has($seat, ['id', 'row', 'column', 'status'])) {
                    $carry->push(new Seat($seat['row'], $seat['column'], $seat['status']));
                }
                return $carry;
            }, collect())
            ->keyBy('id');

        return $this->complete('seats have been received');
    }

    /**
     * The input keys required by this action.
     *
     * @return array
     */
    public function required()
    {
        return [
            'seats', // array
            'venue', // \App\Models\Venue
        ];
    }
}

<?php

namespace App\Actions;

use App\Models\Seat;
use App\Models\Venue;
use StageRightLabs\Actions\Action;

/**
 * Generate a set of empty seats for a venue.
 *
 * Required:
 *  - venue (\App\Models\Venue)
 */
class GenerateSeats extends Action
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
        $seats = collect();

        for ($i = 1; $i <= $this->venue->rows; $i++) {
            for ($j = 1; $j <= $this->venue->columns; $j++) {
                $seats->push(new Seat($i, $j));
            }
        }

        $this->venue->seats = $seats->keyBy('id');

        return $this->complete('seats generated');
    }

    /**
     * The input keys required by this action.
     *
     * @return array
     */
    public function required()
    {
        return [
            'venue', // \App\Models\Venue
        ];
    }
}

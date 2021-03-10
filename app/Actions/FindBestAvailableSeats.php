<?php

namespace App\Actions;

use App\Models\Venue;
use StageRightLabs\Actions\Action;

/**
 * Find the best available seats for a venue that may already have seats booked.
 *
 * Required:
 *  - requested_seat_count (int)
 *  - venue (\App\Models\Venue)
 */
class FindBestAvailableSeats extends Action
{
    /**
     * The seats that have been selected, if any.
     *
     * @var array
     */
    public $determination;

    /**
     * Handle the action.
     *
     * @param Action|array $input
     * @return self
     */
    public function handle($input = [])
    {
        $count = $input['requested_seat_count'];
        $venue = $input['venue'];

        // We can't do anything if the venue has no seats.
        if (empty($venue->seats)) {
            $this->determination = [];
            return $this->fail('no seats available');
        }

        // We won't seat a group that is larger than the number of seats in a row.
        if ($count > $venue->columns) {
            $this->determination = [];
            return $this->fail('no seats available');
        }

        // Determine the center most column in this venue.
        $center = intval(ceil($venue->columns / 2));

        // Find the first row with a contiguous set of seats
        $row =
            // sort the seats by order
            $venue->seats->sort(function ($a, $b) {
                return $a->rowNumber == $b->rowNumber
                    ? $a->column <=> $b->column
                    : $a->rowNumber <=> $b->rowNumber;
            })
            // Remove seats that are already booked.
            ->filter(function ($seat) {
                return $seat->isAvailable();
            })
            // Group the seats by row.
            ->groupBy('row')
            // Remove the array keys
            ->values()
            // Remove rows that don't have enough seats.
            ->reject(function ($row) use ($count) {
                return $row->count() < $count;
            })
            // Organize the row into sets of contiguous seats.
            ->map(function ($row) use ($count) {
                $sets = collect();

                $row->each(function ($seat) use ($row, $count, &$sets) {
                    $set = collect()->push($seat);
                    $column = $seat->column;

                    while ($set->count() < $count) {
                        // If this is an adjacent seat, add it to the set
                        $set->push($row->first(function ($value) use ($column) {
                            return $value->column == $column + 1;
                        }));
                        $column++;
                    }

                    // Save the set if it has the correct number of valid seats
                    if ($set->filter()->count() == $count) {
                        $sets->push($set);
                    }
                });

                // We will only return sets with valid seats
                return $sets;
            })
            // Remove rows that don't have contiguous seats available
            ->filter(function ($row) {
                return $row->isNotEmpty();
            })
            ->first();

        // Find the best set of seats in the selected row.
        $seats = $row
            // Sort by proximity to center.
            // If the proximity is the same, default to House Left.
            ->sort(function ($a, $b) use ($center) {

                $proximityA = abs($a->pluck('column')->avg() - $center);
                $proximityB = abs($b->pluck('column')->avg() - $center);

                return $proximityA == $proximityB
                    ? $a->min('column') <=> $b->min('column')
                    : $proximityA <=> $proximityB;
            })
            ->first();

        if ($seats->isEmpty()) {
            $this->determination = [];
            return $this->fail('no seats available');
        }

        $this->determination = $seats->pluck('id')->toArray();

        return $this->complete('seats have been selected.');
    }

    /**
     * The input keys required by this action.
     *
     * @return array
     */
    public function required()
    {
        return [
            'requested_seat_count', // int
            'venue', // \App\Models\Venue
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Venue;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Actions\ReceiveSeats;
use App\Http\Controllers\Controller;
use App\Actions\FindBestAvailableSeats;
use Illuminate\Support\Facades\Validator;

class FindSeatsAPI extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Validate the payload contents.
        $validator = Validator::make($request->all(), [
            'requested_seat_count' => 'required|numeric',
            'seats' => 'required',
            'venue' => 'required',
        ], [
            'requested_seat_count.required'  => 'The requested seat count is missing, or the JSON is malformed.',
            'seats.required'  => 'The seats description is missing, or the JSON is malformed.',
            'venue.required'  => 'The venue description is missing, or the JSON is malformed.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'seats' => [],
                'error' => $validator->messages()
            ], 422);
        }

        // Pull the row count from the request.
        $rows = Arr::get($request->input('venue'), 'layout.rows');
        if (!$rows) {
            return $this->error('Could not determine the number of rows in the venue.');
        }

        if ($rows > 702) {
            return $this->error('You may not specify more than 702 rows.');
        }

        // Pull the column count from the request.
        $columns = Arr::get($request->input('venue'), 'layout.columns');
        if (!$columns) {
            return $this->error('Could not determine the number of columns in the venue.');
        }

        // Initiate our Venue and Seats
        $preparation = ReceiveSeats::execute([
            'seats' => $request->input('seats', []),
            'venue' => new Venue($rows, $columns),
        ]);

        if ($preparation->failed()) {
            return $this->error('There was a problem parsing the venue');
        }

        // Attempt to find the best available seats.
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => $request->input('requested_seat_count'),
            'venue' => $preparation->venue,
        ]);

        if ($action->failed()) {
            return $this->error($action->getMessage());
        }

        // Return the selected seats.
        return response()->json([
            'seats' => $action->determination,
            'error' => '',
        ]);
    }

    /**
     * Return a generic error response.
     *
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    protected function error($message = 'something went wrong')
    {
        return response()->json([
            'seats' => [],
            'error' => $message,
        ], 422);
    }
}

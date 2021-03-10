<?php

namespace App\Http\Controllers\Api;

use App\Models\Venue;
use App\Actions\GenerateSeats;
use App\Http\Controllers\Controller;

class GenerateVenueAPI extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($rows = null, $columns = null)
    {
        if(!$rows) {
            return $this->error('No row count specified.');
        }

        if (!$columns) {
            return $this->error('No column count specified.');
        }

        $action = GenerateSeats::execute([
            'venue' => new Venue(intval($rows), intval($columns)),
        ]);

        if ($action->failed()) {
            return $this->error('venue generation failed.');
        }

        return response()->json($action->venue->toArray());
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
            'error' => $message,
        ], 422);
    }
}

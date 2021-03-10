@extends('layouts.app')

@section('title', 'API Documentation')

@section('content')
<h2 class="text-3xl mb-4"><a id="seats">POST '<code>/api/seats</code>' - Find the best available seats in a venue</a></h2>
<p class="mb-4">
  Submit a POST request to the '<code>/api/seats</code>' endpoint to have the system determine the best available
  seats in the provided venue. The JSON payload must be structured like this:
</p>

<pre class="rounded bg-gray-100 p-2 mb-4"><code>{
    "requested_seat_count": 3,
    "venue": {
        "layout": {
            "rows": 10,
            "columns": 50
        }
    },
    "seats": {
        "a1": {
            "id": "a1",
            "row": "a",
            "column": 1,
            "status": "AVAILABLE"
        },
        "b5": {
            "id": "b5",
            "row": "b",
            "column": 5,
            "status": "AVAILABLE"
        },
        "h7": {
            "id": "h7",
            "row": "h",
            "column": 7,
            "status": "AVAILABLE"
        },
        // ...
    }
  }
}</code></pre>

<p class="mb-4">
  You can substitute your own '<code>requested_seat_count</code>', '<code>venue</code>' or <code>seats</code> as necessary. Each `seat`
  must have a '<code>status</code>' of either "BOOKED" or "AVAILABLE".
</p>

<p class="mb-4">A successful response will look like this:</p>

<pre class="rounded bg-gray-100 p-2 mb-4"><code>{
    "seats": [
        "b1",
        "b2",
        "b3"
    ],
    "error": ""
}
</code></pre>

<p class="mb-4">An error response will look like this:</p>

<pre class="rounded bg-gray-100 p-2 mb-4"><code>{
    "seats": [],
    "error": "No row count specified."
}
</code></pre>

<p class="mb-4">
  The `error` value in the response will either be a string or an object representing a collection of
  errors.
</p>

<h2 class="text-3xl mb-4"><a id="venue">GET '<code>/api/venue/{rows}/{columns}</code>' - Generate a JSON representation of a Venue</a></h2>
<p class="mb-4">
  Submit a GEt request to the '<code>/api/venue/{rows}/{columns}</code>' endpoint to have the system generate a JSON description of a venue.  The '<code>rows</code>' and '<code>columns</code>' must be integers.  The system will not let you create a venue with more than 702 rows.
</p>

<p class="mb-4">A successful response will look like this:</p>

<pre class="rounded bg-gray-100 p-2 mb-4"><code>{
    "venue": {
        "layout": {
            "rows": 2,
            "columns": 2
        }
    },
    "seats": {
        "a1": {
            "id": "a1",
            "row": "a",
            "column": 1,
            "status": "AVAILABLE"
        },
        "a2": {
            "id": "a2",
            "row": "a",
            "column": 2,
            "status": "AVAILABLE"
        },
        "b1": {
            "id": "b1",
            "row": "b",
            "column": 1,
            "status": "AVAILABLE"
        },
        "b2": {
            "id": "b2",
            "row": "b",
            "column": 2,
            "status": "AVAILABLE"
        }
    }
}
</code></pre>
@endsection

# Best Available Seat

This repository my solution to the 'Best Available Seat' code challenge.  A solution can be retrieved via API or the web.  See below for information about local development and running tests.

This application has been deployed to Heroku:  https://best-available-seat.herokuapp.com/

## API Documentation

### POST `/api/seats`  - Find the best available seats in a venue.

Submit a POST request to the `/api/seats` endpoint to have the system determine the best available seats in the provided venue.  The JSON payload must be structured like this:

```json
{
    "requested_seat_count": 2,
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
        }
        // ...
    }
}
```
You can substitute your own `requested_seat_count`, `venue` or `seats` as necessary.  Each `seat` must have a `status` of either 'BOOKED' or 'AVAILABLE'.

A successful response will look like this:

```json
{
  "seats": [
    "b1",
    "b2",
    "b3"
  ],
  "error": ""
}
```

An error response will look like this:

```json
{
  "seats": [],
  "error": "No row count specified."
}
```

The `error` value in the response will either be a string or an object representing a collection of errors.


### GET `/api/venue/{rows}/{columns}` - Generate a JSON representation of a Venue

Submit a GEt request to the `/api/venue/{rows}/{columns}` endpoint to have the system generate a JSON description of a venue. The `rows` and `columns` must be integers. The system will not let you create a venue with more than 702 rows.

A successful response will look like this:

```
{
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
```

## Local Development

To run this application locally you must have [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) installed on your host machine.

1. Clone this repository to your host machine.
2. Build the docker images: `docker-compose build`
3. Install the PHP dependencies: `docker-compose run --rm php composer install`
4. Install the Node dependencies: `docker-compose run --rm node npm install`
5. Build the assets: `docker-compose run --rm node npm run local`
6. Spin up the docker services: `docker-compose up -d`

You should now be able to reach the application by pointing your browser to `http://localhost:8000`.

To run the test suite, use `docker-compose run --rm php vendor/bin/phpunit`

## Implementation Notes

- This application is built in PHP using the Laravel PHP framework.  The web views are built on top of Laravel Livewire.
- The seat definitions in the JSON payload will represent **all** of the seats in the venue; any seat not listed in the `seats` object will not be available for analysis.
- The core functionality for finding the best available seats is defined in the `app/Actions/FindBestAvailableSeats.php` class.
- For the sake of this exercise I have assumed that groups of patrons will not be split across rows; if there are more patrons than there are seat columns available then the group will not be seated.
- I have also assumed that no venue will have more than 702 rows of seats. ('a' through 'zz'.)

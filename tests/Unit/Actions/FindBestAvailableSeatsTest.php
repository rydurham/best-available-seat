<?php

namespace Tests\Unit\Actions;

use App\Actions\FindBestAvailableSeats;
use App\Models\Venue;
use App\Actions\GenerateSeats;
use App\Actions\ReceiveSeats;
use PHPUnit\Framework\TestCase;

class FindBestAvailableSeatsTest extends TestCase
{
    /** @test */
    public function it_finds_the_best_available_seats_when_no_seats_are_booked()
    {
        $preparation = GenerateSeats::execute([
            'venue' => new Venue(5, 5),
        ]);
        $venue = $preparation->venue;

        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 3,
            'venue' => $venue,
        ]);

        $this->assertTrue($action->completed());
        $this->assertEquals(['a2', 'a3', 'a4'], $action->determination);
    }

    /** @test */
    public function it_finds_the_best_available_seats_when_seats_are_booked()
    {
        // Create a venue with booked seats.  'A' == Available, 'B' == Booked
        // a: [A, B, B, B, A]
        // b: [A, A, A, B, B]
        // c: [A, A, A, A, A]
        // d: [A, A, A, A, A]
        // e: [A, A, A, A, A]
        $json = '{"a1":{"id":"a1","row":"a","column":1,"status":"AVAILABLE"},"a2":{"id":"a2","row":"a","column":2,"status":"BOOKED"},"a3":{"id":"a3","row":"a","column":3,"status":"BOOKED"},"a4":{"id":"a4","row":"a","column":4,"status":"BOOKED"},"a5":{"id":"a5","row":"a","column":5,"status":"AVAILABLE"},"b1":{"id":"b1","row":"b","column":1,"status":"AVAILABLE"},"b2":{"id":"b2","row":"b","column":2,"status":"AVAILABLE"},"b3":{"id":"b3","row":"b","column":3,"status":"AVAILABLE"},"b4":{"id":"b4","row":"b","column":4,"status":"BOOKED"},"b5":{"id":"b5","row":"b","column":5,"status":"BOOKED"},"c1":{"id":"c1","row":"c","column":1,"status":"AVAILABLE"},"c2":{"id":"c2","row":"c","column":2,"status":"AVAILABLE"},"c3":{"id":"c3","row":"c","column":3,"status":"AVAILABLE"},"c4":{"id":"c4","row":"c","column":4,"status":"AVAILABLE"},"c5":{"id":"c5","row":"c","column":5,"status":"AVAILABLE"},"d1":{"id":"d1","row":"d","column":1,"status":"AVAILABLE"},"d2":{"id":"d2","row":"d","column":2,"status":"AVAILABLE"},"d3":{"id":"d3","row":"d","column":3,"status":"AVAILABLE"},"d4":{"id":"d4","row":"d","column":4,"status":"AVAILABLE"},"d5":{"id":"d5","row":"d","column":5,"status":"AVAILABLE"},"e1":{"id":"e1","row":"e","column":1,"status":"AVAILABLE"},"e2":{"id":"e2","row":"e","column":2,"status":"AVAILABLE"},"e3":{"id":"e3","row":"e","column":3,"status":"AVAILABLE"},"e4":{"id":"e4","row":"e","column":4,"status":"AVAILABLE"},"e5":{"id":"e5","row":"e","column":5,"status":"AVAILABLE"}}';
        $action = ReceiveSeats::execute([
            'seats' => json_decode($json, $assoc = true),
            'venue' => new Venue(5, 5),
        ]);
        $venue = $action->venue;

        // Find the best single seat
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 1,
            'venue' => $venue,
        ]);
        $this->assertTrue($action->completed());
        $this->assertEquals(['a1'], $action->determination);

        // Find the best group of two seats
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 2,
            'venue' => $venue,
        ]);
        $this->assertTrue($action->completed());
        $this->assertEquals(['b2', 'b3'], $action->determination);

        // Find the best group of three seats
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 3,
            'venue' => $venue,
        ]);
        $this->assertTrue($action->completed());
        $this->assertEquals(['b1', 'b2', 'b3'], $action->determination);

        // Find the best group of four seats
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 4,
            'venue' => $venue,
        ]);
        $this->assertTrue($action->completed());
        $this->assertEquals(['c1', 'c2', 'c3', 'c4'], $action->determination);

        // Find the best group of five seats
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 5,
            'venue' => $venue,
        ]);
        $this->assertTrue($action->completed());
        $this->assertEquals(['c1', 'c2', 'c3', 'c4', 'c5'], $action->determination);

        // Find the best group of six seats (not possible...)
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 6,
            'venue' => $venue,
        ]);
        $this->assertFalse($action->completed());
        $this->assertEquals([], $action->determination);
    }

    /** @test */
    public function it_requires_a_seat_count()
    {
        $json = '{"a1":{"id":"a1","row":"a","column":1,"status":"AVAILABLE"},"a2":{"id":"a2","row":"a","column":2,"status":"BOOKED"},"a3":{"id":"a3","row":"a","column":3,"status":"BOOKED"},"a4":{"id":"a4","row":"a","column":4,"status":"BOOKED"},"a5":{"id":"a5","row":"a","column":5,"status":"AVAILABLE"},"b1":{"id":"b1","row":"b","column":1,"status":"AVAILABLE"},"b2":{"id":"b2","row":"b","column":2,"status":"AVAILABLE"},"b3":{"id":"b3","row":"b","column":3,"status":"AVAILABLE"},"b4":{"id":"b4","row":"b","column":4,"status":"BOOKED"},"b5":{"id":"b5","row":"b","column":5,"status":"BOOKED"},"c1":{"id":"c1","row":"c","column":1,"status":"AVAILABLE"},"c2":{"id":"c2","row":"c","column":2,"status":"AVAILABLE"},"c3":{"id":"c3","row":"c","column":3,"status":"AVAILABLE"},"c4":{"id":"c4","row":"c","column":4,"status":"AVAILABLE"},"c5":{"id":"c5","row":"c","column":5,"status":"AVAILABLE"},"d1":{"id":"d1","row":"d","column":1,"status":"AVAILABLE"},"d2":{"id":"d2","row":"d","column":2,"status":"AVAILABLE"},"d3":{"id":"d3","row":"d","column":3,"status":"AVAILABLE"},"d4":{"id":"d4","row":"d","column":4,"status":"AVAILABLE"},"d5":{"id":"d5","row":"d","column":5,"status":"AVAILABLE"},"e1":{"id":"e1","row":"e","column":1,"status":"AVAILABLE"},"e2":{"id":"e2","row":"e","column":2,"status":"AVAILABLE"},"e3":{"id":"e3","row":"e","column":3,"status":"AVAILABLE"},"e4":{"id":"e4","row":"e","column":4,"status":"AVAILABLE"},"e5":{"id":"e5","row":"e","column":5,"status":"AVAILABLE"}}';
        $preparation = ReceiveSeats::execute([
            'seats' => json_decode($json, $assoc = true),
            'venue' => new Venue(5, 5),
        ]);
        $venue = $preparation->venue;

        $action = FindBestAvailableSeats::execute([
            'venue' => $venue
        ]);

        $this->assertFalse($action->completed());
    }

    /** @test */
    public function it_requires_a_venue()
    {
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 1,
        ]);

        $this->assertFalse($action->completed());
    }

    /** @test */
    public function it_requires_a_venue_with_seats()
    {
        $action = FindBestAvailableSeats::execute([
            'requested_seat_count' => 1,
            'venue' => new Venue(5, 5),
        ]);

        $this->assertFalse($action->completed());
    }
}

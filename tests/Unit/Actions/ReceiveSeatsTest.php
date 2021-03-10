<?php

namespace Tests\Unit\Actions;

use App\Models\Venue;
use App\Actions\ReceiveSeats;
use PHPUnit\Framework\TestCase;

class ReceiveSeatsTest extends TestCase
{
    /** @test */
    public function it_receives_seats_from_a_decoded_json_object()
    {
        $json = '{"a1":{"id": "a1","row": "a","column": 1,"status": "AVAILABLE"},"b5": {"id": "b5","row": "b","column": 5,"status": "AVAILABLE"},"h7": {"id": "h7","row": "h","column": 7,"status": "AVAILABLE"}}';
        $venue = new Venue(1, 4);

        $action = ReceiveSeats::execute([
            'seats' => json_decode($json, $assoc = true),
            'venue' => $venue,
        ]);

        $expectedSeats = [
            'a1' => [
                'id' => 'a1',
                'row' => 'a',
                'column' => 1,
                'status' => 'AVAILABLE',
            ],
            'b5' => [
                'id' => 'b5',
                'row' => 'b',
                'column' => 5,
                'status' => 'AVAILABLE',
            ],
            'h7' => [
                'id' => 'h7',
                'row' => 'h',
                'column' => 7,
                'status' => 'AVAILABLE',
            ]
        ];

        $this->assertTrue($action->completed());
        $this->assertEquals($expectedSeats, $action->venue->seats->toArray());
    }

    /** @test */
    public function it_requires_a_venue()
    {
        $json = '{"a1":{"id": "a1","row": "a","column": 1,"status": "AVAILABLE"},"b5": {"id": "b5","row": "b","column": 5,"status": "AVAILABLE"},"h7": {"id": "h7","row": "h","column": 7,"status": "AVAILABLE"}}';

        $action = ReceiveSeats::execute([
            'seats' => json_decode($json, $assoc = true),
        ]);

        $this->assertFalse($action->completed());
    }

    /** @test */
    public function it_requires_seats()
    {
        $venue = new Venue(1, 4);

        $action = ReceiveSeats::execute([
            'venue' => $venue,
        ]);

        $this->assertFalse($action->completed());
    }
}

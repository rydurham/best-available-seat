<?php

namespace Tests\Unit\Actions;

use App\Models\Venue;
use App\Actions\GenerateSeats;
use PHPUnit\Framework\TestCase;

class GenerateSeatsTest extends TestCase
{
    /** @test */
    public function it_generate_seats()
    {
        $venue = new Venue(1, 4);
        $action = GenerateSeats::execute([
            'venue' => $venue,
        ]);

        $expectedSeats = [
            'a1' => [
                'id' => 'a1',
                'row' => 'a',
                'column' => 1,
                'status' => 'AVAILABLE',
            ],
            'a2' => [
                'id' => 'a2',
                'row' => 'a',
                'column' => 2,
                'status' => 'AVAILABLE',
            ],
            'a3' => [
                'id' => 'a3',
                'row' => 'a',
                'column' => 3,
                'status' => 'AVAILABLE',
            ],
            'a4' => [
                'id' => 'a4',
                'row' => 'a',
                'column' => 4,
                'status' => 'AVAILABLE',
            ],
        ];

        $this->assertTrue($action->completed());
        $this->assertEquals($expectedSeats, $action->venue->seats->toArray());
    }

    /** @test */
    public function it_requires_a_venue()
    {
        $action = GenerateSeats::execute();

        $this->assertFalse($action->completed());
    }
}

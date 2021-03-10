<?php

namespace Tests\Unit;

use Exception;
use App\Models\Venue;
use PHPUnit\Framework\TestCase;

class VenueTest extends TestCase
{
    /** @test */
    public function venues_cannot_have_more_than_702_rows()
    {
        $this->expectException(Exception::class);
        $venue = new Venue(703, 10);
    }
}

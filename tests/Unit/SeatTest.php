<?php

namespace Tests\Unit;

use App\Models\Seat;
use PHPUnit\Framework\TestCase;

class SeatTest extends TestCase
{
    /** @test */
    public function it_generates_labels_for_row_numbers()
    {
        $this->assertEquals('a', Seat::labelForRow(1));
        $this->assertEquals('e', Seat::labelForRow(5));
        $this->assertEquals('i', Seat::labelForRow(9));
        $this->assertEquals('o', Seat::labelForRow(15));
        $this->assertEquals('u', Seat::labelForRow(21));
        $this->assertEquals('y', Seat::labelForRow(25));
        $this->assertEquals('z', Seat::labelForRow(26));
        $this->assertEquals('aa', Seat::labelForRow(27));
        $this->assertEquals('az', Seat::labelForRow(52));
        $this->assertEquals('ba', Seat::labelForRow(53));
        $this->assertEquals('zz', Seat::labelForRow(702));
    }

    /** @test */
    public function it_generates_row_numbers_from_labels()
    {
        $this->assertEquals(1, Seat::numberFromLabel('a'));
        $this->assertEquals(5, Seat::numberFromLabel('e'));
        $this->assertEquals(9, Seat::numberFromLabel('i'));
        $this->assertEquals(15, Seat::numberFromLabel('o'));
        $this->assertEquals(21, Seat::numberFromLabel('u'));
        $this->assertEquals(25, Seat::numberFromLabel('y'));
        $this->assertEquals(26, Seat::numberFromLabel('z'));
        $this->assertEquals(27, Seat::numberFromLabel('aa'));
        $this->assertEquals(52, Seat::numberFromLabel('az'));
        $this->assertEquals(53, Seat::numberFromLabel('ba'));
        $this->assertEquals(702, Seat::numberFromLabel('zz'));
    }
}

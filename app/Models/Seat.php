<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Support\Arrayable;

class Seat implements Arrayable
{
    /**
     * Define the 'available' status indicator.
     */
    const AVAILABLE = 'AVAILABLE';

    /**
     * Defined the 'booked' status indicator.
     */
    const BOOKED = 'BOOKED';

    /**
     * The seat identifier.
     *
     * @var string
     */
    public $id;

    /**
     * The seat row.
     *
     * @var
     */
    public $row;

    /**
     * The seat row as an integer.
     *
     * @var int
     */
    public $rowNumber;

    /**
     * The seat column.
     *
     * @var int
     */
    public $column;

    /**
     * The seat status: AVAILABLE or BOOKED
     *
     * @var string
     */
    public $status = self::AVAILABLE;

    /**
     * Is this seat available?
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->status == self::AVAILABLE;
    }

    /**
     * Create a new seat.
     *
     * @param string|int $row
     * @param int $column
     * @param string $status
     */
    public function __construct($row, $column, $status = self::AVAILABLE)
    {
        $this->row = is_string($row) ? $row : self::labelForRow($row);
        $this->rowNumber = is_int($row) ? $row : self::numberFromLabel($row);
        $this->column = $column;

        $this->id = $this->row . $this->column;

        $this->status = $status;
    }

    /**
     * Convert a row number to a row letter.  For the sake of convenience we
     * will not go past 702 rows.
     *
     * @param int $row
     * @return string
     */
    public static function labelForRow($row)
    {
        if ($row <= 0 || $row > 702) {
            throw new Exception('row out of bounds');
        }

        $label = '';
        $floor = intval(floor($row / 26));
        $chr = $row % 26;

        // If $chr is zero we can assume it should be a 'z'.
        if ($chr == 0) {
            $chr += 26;
            $floor --;
        }

        // A floor greater than zero indicates a double letter seat label.
        if ($floor > 0) {
            $label .= chr($floor + 96);
        }

        $label .= chr($chr + 96);

        return $label;
    }

    /**
     * Convert a row label into an integer.
     *
     * @param string $label
     * @return int
     */
    public static function numberFromLabel($label)
    {
        $sum = 0;

        foreach (array_reverse(str_split($label)) as $index => $letter) {
            if ($index == 0) {
                $sum += ord($letter) - 96;
            } else {
                $sum += (ord($letter) - 96) * 26;
            }
        }

        return $sum;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'row' => $this->row,
            'column' => $this->column,
            'status' => $this->status,
        ];
    }
}

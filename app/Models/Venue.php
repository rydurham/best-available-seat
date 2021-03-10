<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Venue implements Arrayable
{
    /**
     * The row count for this venue.
     *
     * @var int
     */
    public $rows;

    /**
     * The column count for this venue.
     *
     * @var int
     */
    public $columns;

    /**
     * The seats in this venue.
     *
     * @var Collection
     */
    public $seats;

    /**
     * Instantiate a new Venue class
     *
     * @param integer $rows
     * @param integer $columns
     */
    public function __construct($rows = 10, $columns = 12) {

        // No venue can have more than 702 rows.
        if ($rows > 702) {
            throw new Exception('Invalid row count');
        }

        $this->rows = $rows;
        $this->columns = $columns;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        return [
            'venue' => [
                'layout' => [
                    'rows' => $this->rows,
                    'columns' => $this->columns,
                ]
            ],
            'seats' => $this->seats->toArray(),
        ];
    }
}

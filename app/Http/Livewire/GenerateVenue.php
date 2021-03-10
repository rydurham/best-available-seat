<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Venue;
use Livewire\Component;
use App\Actions\GenerateSeats;

class GenerateVenue extends Component
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        'rows' => 'required|min:1|max:702|numeric',
        'columns' => 'required|min:1|numeric',
    ];

    /**
     * Validation messages.
     *
     * @var array
     */
    protected $messages = [
        'rows.min' => 'You must indicate at least one row.',
        'rows.max' => 'No more than 702 rows allowed',
        'columns.min' => 'You must indicate at least one column.',
    ];

    /**
     * The number of rows we are working with.
     *
     * @var int
     */
    public $rows = 10;

    /**
     * The number of columns we are working with.
     *
     * @var int
     */
    public $columns = 10;

    /**
     * The generated JSON.
     *
     * @var string
     */
    public $json;

    /**
     * Render the page.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.generate-venue');
    }

    /**
     * Generate a JSON representation of a Venue.
     *
     * @return void
     */
    public function generate()
    {
        $this->validate();

        $action = GenerateSeats::execute([
            'venue' => new Venue(intval($this->rows), intval($this->columns)),
        ]);

        if ($action->failed()) {
            throw new Exception('Venue generation failed.');
        }

        $this->json = json_encode($action->venue->toArray(), JSON_PRETTY_PRINT);
    }
}

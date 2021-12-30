<?php

namespace App;

use App\Game;
use App\Exceptions\InvalidStateTransitionException;
use App\Exceptions\InvalidFlagSymbolException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cell extends Model
{
    use SoftDeletes;

    // Valid states
    const COVERED = 'covered';
    const UNCOVERED = 'uncovered';
    const FLAGGED = 'flagged';

    protected $guarded = ['id'];

    /**
     * Get the game that owns the cell.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Returns true if cell's state is uncovered
     */
    public function getUncoveredAttribute()
    {
        return $this->state == Cell::UNCOVERED;
    }

    /**
     * Returns true if cell's state is covered
     */
    public function getCoveredAttribute()
    {
        return $this->state == Cell::COVERED;
    }

    /**
     * Uncovers cell if possible. Needs a lot of work still.
     */
    public function uncover()
    {
        if ($this->state != Cell::UNCOVERED) {
            $this->state = Cell::UNCOVERED;
            $this->save();
            //Still needs to uncover surrounding cells
            return $this;
        } else {
            throw new InvalidStateTransitionException();
        }
    }

    public function flag($symbol = null)
    {
        if ($this->state == Cell::COVERED) {
            $this->state = Cell::FLAGGED;

            if (in_array($symbol, config('flag-symbols'))) {
                $this->flagged = $symbol;
            } else {
                throw new InvalidFlagSymbolException();
            }

            $this->save();

            return $this;
        } else {
            throw new InvalidStateTransitionException();
        }
    }
}

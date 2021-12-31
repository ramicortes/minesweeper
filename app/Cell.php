<?php

namespace App;

use App\Game;
use App\Exceptions\InvalidStateTransitionException;
use App\Exceptions\InvalidFlagSymbolException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Cell extends Model
{
    use SoftDeletes;

    // Valid states
    const COVERED = 'covered';
    const UNCOVERED = 'uncovered';
    const FLAGGED = 'flagged';

    protected $guarded = ['id'];

    // Not hidding id since I didn't get to switch to external-id for urls
    protected $hidden = ['game_id', 'created_at', 'updated_at', 'deleted_at'];

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

            $adjacentCellsWithMines = $this->getAdjacentCoveredCellsWithMines()->count();

            if ($adjacentCellsWithMines == 0) {
                $this->getAdjacentCoveredCellsWithoutMines()->each(function ($cell) {
                    $cell->uncover();
                });
            } else {
                $this->adjacent_mines = $adjacentCellsWithMines;
                $this->save();
            }

            return $this;
        } else {
            throw new InvalidStateTransitionException();
        }
    }

    public function flag($symbol = null)
    {
        if ($this->state == Cell::COVERED || $this->state == Cell::FLAGGED) {
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

    public function getAdjacentCoveredCellsWithoutMines(): Collection
    {
        return Cell::where('game_id', '=', $this->game->id)
            ->where('state', 'not like', 'uncovered')
            ->where('mine', 'like', false)
            ->whereBetween('row', [$this->row - 1, $this->row + 1])
            ->whereBetween('column', [$this->column - 1, $this->column + 1])
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function getAdjacentCoveredCellsWithMines(): Collection
    {
        return Cell::where('game_id', '=', $this->game->id)
            ->where('state', 'not like', 'uncovered')
            ->where('mine', 'like', true)
            ->whereBetween('row', [$this->row - 1, $this->row + 1])
            ->whereBetween('column', [$this->column - 1, $this->column + 1])
            ->where('id', '!=', $this->id)
            ->get();
    }
}

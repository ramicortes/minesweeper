<?php

namespace App;

use App\Cell;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'ended_at' => 'datetime:Y-m-d',
    ];

    /**
     * Get the cells for the game.
     */
    public function cells()
    {
        return $this->hasMany(Cell::class);
    }

    /**
     * Get the user that owns the game.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns true if game has ended, false otherwise
     */
    public function getEndedAttribute()
    {
        return $this->ended_at != null;
    }

    /**
     * Returns how long has the game lasted in minutes
     */
    public function getDurationAttribute()
    {
        $endGame = $this->ended_at ? $this->ended_at : Carbon::now();
        return $this->created_at->diffInMinutes($endGame);
    }

    /**
     * Uncovers a cell and ends the game if it had a mine
     */
    public function uncoverCell(Cell $cell)
    {
        $cell = $cell->uncover();

        if ($cell->mine) {
            $this->ended_at = Carbon::now();
            $this->save();
        }
    }

    /**
     * Creates the cells for the game according to user input
     */
    public function generateBoard()
    {
        // There must be a thousand best ways to do this but I was running out of time and
        // it seems this wasn't high priority according to git repository
        $length = ($this->rows * $this->columns) - 1;
        $board = collect(range(0, $length))->shuffle();
        $row = 0;
        $column = 0;
        $game = $this;

        $board->each(function ($cellNumber) use (&$row, &$column, $game) {
            $game->cells()->create([
                'row' => $row,
                'column' => $column,
                'state' => 'covered',
                'flagged' => null,
                'mine' => $game->mines > $cellNumber,
            ]);

            // Realized there was no need to store row and column since board knows how to display them but I'm
            // running out of time. This increases de indexes from "top to bottom" and "left to right".
            $row++;
            if ($row >= $game->rows) {
                $row = 0;
                $column++;
            }
        });
    }
}

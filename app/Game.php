<?php

namespace App;

use App\Cell;
use App\User;
use App\Exceptions\InvalidStateTransitionException;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
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
}

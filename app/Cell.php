<?php

namespace App;

use App\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cell extends Model
{
    use SoftDeletes;

    /**
     * Get the game that owns the cell.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}

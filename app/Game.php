<?php

namespace App;

use App\Cell;
use App\User;
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
}

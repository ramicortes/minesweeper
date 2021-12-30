<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cell;
use App\Game;
use Faker\Generator as Faker;

$factory->define(Cell::class, function (Faker $faker) {
    $game = factory(Game::class)->create();
    return [
        'row' => $faker->numberBetween(0, $game->rows - 1),
        'column' => $faker->numberBetween(0, $game->columns - 1),
        'state' => $faker->randomElement(['covered', 'uncovered', 'flagged']),
        'flagged' => $faker->randomElement([null, 'flag', 'mark']),
        'mine' => $faker->boolean(),
        'game_id' => $game->id,
    ];
});
